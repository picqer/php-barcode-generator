<?php

namespace Picqer\Barcode\Types;

use Picqer\Barcode\Barcode;
use Picqer\Barcode\BarcodeBar;
use Picqer\Barcode\Exceptions\InvalidCharacterException;

/*
 * CODE11 barcodes.
 * Used primarily for labeling telecommunications equipment
 */

class TypeCode11 implements TypeInterface
{
    protected array $conversionTable = [
        '0' => '111121',
        '1' => '211121',
        '2' => '121121',
        '3' => '221111',
        '4' => '112121',
        '5' => '212111',
        '6' => '122111',
        '7' => '111221',
        '8' => '211211',
        '9' => '211111',
        '-' => '112111',
        'S' => '112211',
    ];

    public function getBarcode(string $code): Barcode
    {
        $barcode = new Barcode($code);

        $code .= $this->getCheckDigitC($code);
        $code .= $this->getCheckDigitK($code);

        $code = 'S' . $code . 'S';

        for ($i = 0; $i < strlen($code); ++$i) {
            if (! isset($this->conversionTable[$code[$i]])) {
                throw new InvalidCharacterException('Char ' . $code[$i] . ' is unsupported');
            }

            $seq = $this->conversionTable[$code[$i]];
            for ($j = 0; $j < strlen($seq); ++$j) {
                if (($j % 2) == 0) {
                    $drawBar = true;
                } else {
                    $drawBar = false;
                }
                $barWidth = $seq[$j];

                $barcode->addBar(new BarcodeBar($barWidth, 1, $drawBar));
            }
        }

        return $barcode;
    }

    private function getCheckDigitC(string $code): string
    {
        $weight = 1;
        $checksum = 0;
        for ($i = (strlen($code) - 1); $i >= 0; --$i) {
            $digit = $code[$i];
            if ($digit == '-') {
                $digitValue = 10;
            } else {
                $digitValue = intval($digit);
            }
            $checksum += ($digitValue * $weight);
            ++$weight;
            if ($weight > 10) {
                $weight = 1;
            }
        }
        $checksum %= 11;
        if ($checksum == 10) {
            $checksum = '-';
        }

        return $checksum;
    }

    private function getCheckDigitK(string $code): string
    {
        if (strlen($code) <= 10) {
            return '';
        }

        $weight = 1;
        $checksum = 0;
        for ($i = (strlen($code) - 1); $i >= 0; --$i) {
            $digit = $code[$i];
            if ($digit == '-') {
                $digitValue = 10;
            } else {
                $digitValue = intval($digit);
            }
            $checksum += ($digitValue * $weight);
            ++$weight;
            if ($weight > 9) {
                $weight = 1;
            }
        }
        $checksum %= 11;

        return (string)$checksum;
    }
}
