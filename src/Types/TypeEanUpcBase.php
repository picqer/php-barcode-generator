<?php

namespace Picqer\Barcode\Types;

use Picqer\Barcode\Barcode;
use Picqer\Barcode\BarcodeBar;
use Picqer\Barcode\Exceptions\InvalidCharacterException;
use Picqer\Barcode\Exceptions\InvalidCheckDigitException;
use Picqer\Barcode\Exceptions\InvalidLengthException;

/*
 * EAN13 and UPC-A barcodes.
 * EAN13: European Article Numbering international retail product code
 * UPC-A: Universal product code seen on almost all retail products in the USA and Canada
 *
 * @param $code (string) code to represent.
 * @param $len (string) barcode type: 8 = EAN8, 13 = EAN13, 12 = UPC-A
 */

abstract class TypeEanUpcBase implements TypeInterface
{
    protected int $length = 13;
    protected bool $upca = false;

    public function getBarcode(string $code): Barcode
    {
        if (strlen(trim($code)) === 0) {
            throw new InvalidLengthException('You should provide a barcode string.');
        }

        $length = $this->length;

        $dataLength = $length - 1;

        // Add zero padding in front
        $code = str_pad($code, $dataLength, '0', STR_PAD_LEFT);

        $checksumDigit = $this->calculateChecksumDigit($code);

        if (strlen($code) == $dataLength) {
            $code .= $checksumDigit;
        } elseif ($checksumDigit !== intval($code[$dataLength])) {
            // If length of given barcode is same as final length, barcode is including checksum
            // Make sure that checksum is the same as we calculated
            throw new InvalidCheckDigitException();
        }

        if ($this->upca) {
            $code = '0' . $code;
            ++$length;
        }

        // Convert digits to bars
        $codes = [
            'A' => [ // left odd parity
                '0' => '0001101',
                '1' => '0011001',
                '2' => '0010011',
                '3' => '0111101',
                '4' => '0100011',
                '5' => '0110001',
                '6' => '0101111',
                '7' => '0111011',
                '8' => '0110111',
                '9' => '0001011'
            ],
            'B' => [ // left even parity
                '0' => '0100111',
                '1' => '0110011',
                '2' => '0011011',
                '3' => '0100001',
                '4' => '0011101',
                '5' => '0111001',
                '6' => '0000101',
                '7' => '0010001',
                '8' => '0001001',
                '9' => '0010111'
            ],
            'C' => [ // right
                '0' => '1110010',
                '1' => '1100110',
                '2' => '1101100',
                '3' => '1000010',
                '4' => '1011100',
                '5' => '1001110',
                '6' => '1010000',
                '7' => '1000100',
                '8' => '1001000',
                '9' => '1110100'
            ]
        ];

        $parities = [
            '0' => ['A', 'A', 'A', 'A', 'A', 'A'],
            '1' => ['A', 'A', 'B', 'A', 'B', 'B'],
            '2' => ['A', 'A', 'B', 'B', 'A', 'B'],
            '3' => ['A', 'A', 'B', 'B', 'B', 'A'],
            '4' => ['A', 'B', 'A', 'A', 'B', 'B'],
            '5' => ['A', 'B', 'B', 'A', 'A', 'B'],
            '6' => ['A', 'B', 'B', 'B', 'A', 'A'],
            '7' => ['A', 'B', 'A', 'B', 'A', 'B'],
            '8' => ['A', 'B', 'A', 'B', 'B', 'A'],
            '9' => ['A', 'B', 'B', 'A', 'B', 'A'],
        ];

        $seq = '101'; // left guard bar
        $barcode = new Barcode($code);
        $half_len = intval(ceil($length / 2));
        if ($length == 8) {
            for ($i = 0; $i < $half_len; ++$i) {
                $seq .= $codes['A'][$code[$i]];
            }
        } else {
            $p = $parities[$code[0]];
            for ($i = 1; $i < $half_len; ++$i) {
                $seq .= $codes[$p[$i - 1]][$code[$i]];
            }
        }
        $seq .= '01010'; // center guard bar
        for ($i = $half_len; $i < $length; ++$i) {
            if (! isset($codes['C'][$code[$i]])) {
                throw new InvalidCharacterException('Char ' . $code[$i] . ' not allowed');
            }
            $seq .= $codes['C'][$code[$i]];
        }
        $seq .= '101'; // right guard bar

        $clen = strlen($seq);
        $w = 0;
        for ($i = 0; $i < $clen; ++$i) {
            $w += 1;
            if (($i == ($clen - 1)) OR (($i < ($clen - 1)) AND ($seq[$i] != $seq[($i + 1)]))) {
                if ($seq[$i] == '1') {
                    $t = true; // bar
                } else {
                    $t = false; // space
                }

                $barcode->addBar(new BarcodeBar($w, 1, $t));
                $w = 0;
            }
        }

        return $barcode;
    }

    protected function calculateChecksumDigit(string $code): int
    {
        // calculate check digit
        $sum_a = 0;
        for ($i = 1; $i < $this->length - 1; $i += 2) {
            $sum_a += intval($code[$i]);
        }
        if ($this->length > 12) {
            $sum_a *= 3;
        }
        $sum_b = 0;
        for ($i = 0; $i < $this->length - 1; $i += 2) {
            $sum_b += intval(($code[$i]));
        }
        if ($this->length < 13) {
            $sum_b *= 3;
        }
        $checksumDigit = ($sum_a + $sum_b) % 10;
        if ($checksumDigit > 0) {
            $checksumDigit = (10 - $checksumDigit);
        }

        return $checksumDigit;
    }
}
