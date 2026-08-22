<?php

namespace Picqer\Barcode\Types;

use Picqer\Barcode\Barcode;
use Picqer\Barcode\BarcodeBar;
use Picqer\Barcode\Helpers\UpcEConverter;

/*
 * UPC-E: zero-suppressed representation of a GTIN-12.
 *
 * Accepts 7 or 8 UPC-E digits (with an optional check digit), or a zero-suppressible
 * UPC-A value containing 11 or 12 digits (with an optional check digit).
 */
class TypeUpcE implements TypeInterface
{
    protected const DIGIT_PATTERNS = [
        'A' => [
            '0' => '0001101',
            '1' => '0011001',
            '2' => '0010011',
            '3' => '0111101',
            '4' => '0100011',
            '5' => '0110001',
            '6' => '0101111',
            '7' => '0111011',
            '8' => '0110111',
            '9' => '0001011',
        ],
        'B' => [
            '0' => '0100111',
            '1' => '0110011',
            '2' => '0011011',
            '3' => '0100001',
            '4' => '0011101',
            '5' => '0111001',
            '6' => '0000101',
            '7' => '0010001',
            '8' => '0001001',
            '9' => '0010111',
        ],
    ];

    protected const PARITIES = [
        '0' => 'BBBAAA',
        '1' => 'BBABAA',
        '2' => 'BBAABA',
        '3' => 'BBAAAB',
        '4' => 'BABBAA',
        '5' => 'BAABBA',
        '6' => 'BAAABB',
        '7' => 'BABABA',
        '8' => 'BABAAB',
        '9' => 'BAABAB',
    ];

    public function getBarcode(string $code): Barcode
    {
        $data = UpcEConverter::normalize($code);
        $encodedDigits = $data->getEncodedDigits();
        $parity = self::PARITIES[$data->getCheckDigit()];

        $sequence = '101';
        for ($position = 0; $position < 6; ++$position) {
            $sequence .= self::DIGIT_PATTERNS[$parity[$position]][$encodedDigits[$position]];
        }
        $sequence .= '010101';

        return $this->createBarcode($data->getHumanReadable(), $sequence);
    }

    protected function createBarcode(string $humanReadable, string $sequence): Barcode
    {
        $barcode = new Barcode($humanReadable);
        $runWidth = 0;
        $sequenceLength = strlen($sequence);

        for ($position = 0; $position < $sequenceLength; ++$position) {
            ++$runWidth;
            $isLastModule = $position === $sequenceLength - 1;
            $changesAfterThisModule = ! $isLastModule && $sequence[$position] !== $sequence[$position + 1];

            if ($isLastModule || $changesAfterThisModule) {
                $barcode->addBar(new BarcodeBar($runWidth, 1, $sequence[$position] === '1'));
                $runWidth = 0;
            }
        }

        return $barcode;
    }
}
