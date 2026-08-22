<?php

namespace Picqer\Barcode\Helpers;

use Picqer\Barcode\Exceptions\InvalidCharacterException;
use Picqer\Barcode\Exceptions\InvalidCheckDigitException;
use Picqer\Barcode\Exceptions\InvalidFormatException;
use Picqer\Barcode\Exceptions\InvalidLengthException;

final class UpcEConverter
{
    public static function normalize(string $code): UpcEData
    {
        self::assertDigits($code);

        return match (strlen($code)) {
            7, 8 => self::fromUpcE($code),
            11, 12 => self::fromUpcA($code),
            default => throw new InvalidLengthException(
                'UPC-E expects 7 or 8 UPC-E digits, or 11 or 12 UPC-A digits.'
            ),
        };
    }

    public static function expand(string $upcE): string
    {
        if (! in_array(strlen($upcE), [7, 8], true)) {
            throw new InvalidLengthException('UPC-E input must contain 7 or 8 digits.');
        }

        self::assertDigits($upcE);

        return self::fromUpcE($upcE)->getGtin12();
    }

    public static function compress(string $upcA): string
    {
        if (! in_array(strlen($upcA), [11, 12], true)) {
            throw new InvalidLengthException('UPC-A input must contain 11 or 12 digits.');
        }

        self::assertDigits($upcA);

        return self::fromUpcA($upcA)->getHumanReadable();
    }

    protected static function fromUpcE(string $code): UpcEData
    {
        if ($code[0] !== '0') {
            throw new InvalidFormatException('UPC-E input must start with the number system digit 0.');
        }

        $encodedDigits = substr($code, 1, 6);
        $gtinWithoutCheckDigit = self::expandEncodedDigits($encodedDigits);

        // Re-compressing verifies the GS1 zero-suppression constraints. Some six-digit
        // combinations can be expanded mechanically but are not valid UPC-E symbols.
        if (self::compressGtinWithoutCheckDigit($gtinWithoutCheckDigit) !== $encodedDigits) {
            throw new InvalidFormatException('The provided digits cannot form a valid UPC-E symbol.');
        }

        $checkDigit = self::calculateCheckDigit($gtinWithoutCheckDigit);
        if (strlen($code) === 8 && $code[7] !== $checkDigit) {
            throw new InvalidCheckDigitException('Provided UPC-E check digit is invalid.');
        }

        return new UpcEData($encodedDigits, $checkDigit, $gtinWithoutCheckDigit . $checkDigit);
    }

    protected static function fromUpcA(string $code): UpcEData
    {
        if ($code[0] !== '0') {
            throw new InvalidFormatException('Only UPC-A values with number system digit 0 can be encoded as UPC-E.');
        }

        $gtinWithoutCheckDigit = substr($code, 0, 11);
        $checkDigit = self::calculateCheckDigit($gtinWithoutCheckDigit);

        if (strlen($code) === 12 && $code[11] !== $checkDigit) {
            throw new InvalidCheckDigitException('Provided UPC-A check digit is invalid.');
        }

        $encodedDigits = self::compressGtinWithoutCheckDigit($gtinWithoutCheckDigit);

        return new UpcEData($encodedDigits, $checkDigit, $gtinWithoutCheckDigit . $checkDigit);
    }

    protected static function expandEncodedDigits(string $digits): string
    {
        [$x1, $x2, $x3, $x4, $x5, $x6] = str_split($digits);

        return match ($x6) {
            '0', '1', '2' => '0' . $x1 . $x2 . $x6 . '0000' . $x3 . $x4 . $x5,
            '3' => '0' . $x1 . $x2 . $x3 . '00000' . $x4 . $x5,
            '4' => '0' . $x1 . $x2 . $x3 . $x4 . '00000' . $x5,
            default => '0' . $x1 . $x2 . $x3 . $x4 . $x5 . '0000' . $x6,
        };
    }

    protected static function compressGtinWithoutCheckDigit(string $gtin): string
    {
        // D11 is 5-9, D7-D10 are zero, and D6 is non-zero.
        if (str_contains('56789', $gtin[10]) && substr($gtin, 6, 4) === '0000' && $gtin[5] !== '0') {
            return substr($gtin, 1, 5) . $gtin[10];
        }

        // D6-D10 are zero and D5 is non-zero.
        if (substr($gtin, 5, 5) === '00000' && $gtin[4] !== '0') {
            return substr($gtin, 1, 4) . $gtin[10] . '4';
        }

        // D4 is 0-2 and D5-D8 are zero.
        if (str_contains('012', $gtin[3]) && substr($gtin, 4, 4) === '0000') {
            return substr($gtin, 1, 2) . substr($gtin, 8, 3) . $gtin[3];
        }

        // D4 is 3-9 and D5-D9 are zero.
        if (str_contains('3456789', $gtin[3]) && substr($gtin, 4, 5) === '00000') {
            return substr($gtin, 1, 3) . substr($gtin, 9, 2) . '3';
        }

        throw new InvalidFormatException('The provided UPC-A value cannot be represented as UPC-E.');
    }

    protected static function calculateCheckDigit(string $code): string
    {
        $sum = 0;
        $weight = 3;

        for ($position = strlen($code) - 1; $position >= 0; --$position) {
            $sum += intval($code[$position]) * $weight;
            $weight = $weight === 3 ? 1 : 3;
        }

        return (string)((10 - ($sum % 10)) % 10);
    }

    protected static function assertDigits(string $code): void
    {
        if ($code === '') {
            throw new InvalidLengthException('You should provide a barcode string.');
        }

        if (preg_match('/^[0-9]+$/D', $code) !== 1) {
            throw new InvalidCharacterException('UPC-E and UPC-A input may only contain digits.');
        }
    }
}
