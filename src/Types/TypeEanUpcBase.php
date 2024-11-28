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
 * UPC-E: Short version of UPC symbol
 *
 * @param $code (string) code to represent.
 * @param $len (string) barcode type: 6 = UPC-E, 8 = EAN8, 13 = EAN13, 12 = UPC-A
 */

abstract class TypeEanUpcBase implements TypeInterface
{
    protected int $length = 13;
    protected bool $upca = false;
    protected bool $upce = false;

    public function getBarcode(string $code): Barcode
    {
        if (strlen(trim($code)) === 0) {
            throw new InvalidLengthException('You should provide a barcode string.');
        }

        $length = $this->length;

        $dataLength = $length - 1;

        // Convert proper UPC-E codes into UPC-A codes.
        if ($this->upce) {
            $strLen = strlen($code);

            if ($strLen === 8) {
                // Parity and checksum digits are provided. Store them for later usage.
                $checksumDigit = $code[7];
                $parityDigit   = $code[0];
                $code          = substr($code, 1, 6);
                $strLen       -= 2;
            }
            if ($strLen === 7) {
                // Either parity or checksum digit is provided.
                if (($code[0] === '0') || ($code[0] === '1')) {
                    // Assume parity digit.
                    $parityDigit = $code[0];
                    $code        = substr($code, 1, 6);
                } else {
                    // Assume checksum digit.
                    $checksumDigit = $code[6];
                    $code          = substr($code, 0, 6);
                }

                --$strLen;
            }
            if ($strLen === 6) {
                // Store original UPC-E code for later usage.
                $upce_code = $code; // Neither parity digit nor checksum digit are printed in UPC-E barcode.

                // Convert UPC-E code into UPC-A code.
                $conversionData = array (
                    '0' => array (2, '00000'),
                    '1' => array (2, '10000'),
                    '2' => array (2, '20000'),
                    '3' => array (3, '00000'),
                    '4' => array (4, '00000'),
                    '5' => array (5, '00005'),
                    '6' => array (5, '00006'),
                    '7' => array (5, '00007'),
                    '8' => array (5, '00008'),
                    '9' => array (5, '00009')
                );
                $lastUpcEDigit  = $code[5];

                if (!isset ($conversionData[$lastUpcEDigit])) {
                    throw new InvalidCharacterException('Char ' . $lastUpcEDigit . ' not allowed');
                }

                $conversionData = $conversionData[$lastUpcEDigit];
                $code           = substr($upce_code, 0, $conversionData[0]);                      // Add all Xs.
                $code          .= $conversionData[1];                                             // Add constant number.
                $code          .= substr($upce_code, $conversionData[0], 5 - $conversionData[0]); // Add all Ns.

                // Add parity digit.
                if (!isset ($parityDigit)) {
                    // Provide '0' as default parity digit.
                    $parityDigit = '0';
                }

                $code = $parityDigit . $code;

                // Add checksum digit.
                if (isset ($checksumDigit)) {
                    $code .= $checksumDigit;
                } // else { The checksum digit will be calculated and added, later. }
            }
        }

        // Add zero padding in front
        $code = str_pad($code, $dataLength, '0', STR_PAD_LEFT);

        $checksumDigit = $this->calculateChecksumDigit($code);

        if (strlen($code) == $dataLength) {
            $code .= $checksumDigit;
        } elseif ($checksumDigit !== intval($code[$dataLength])) {
            // If length of given barcode is same as final length, barcode is including checksum
            // Make sure that checksum is the same as we calculated
            if ($this->upce && isset ($upce_code)) {
                // It's possible that parity digit was not provided and the assumed '0' is wrong. Try again with '1'.
                $parityDigit = '1';
                $code[0]     = $parityDigit;
                $checksumDigit = $this->calculateChecksumDigit($code);

                if ($checksumDigit !== intval($code[$dataLength])) {
                    throw new InvalidCheckDigitException();
                }
            } else {
                throw new InvalidCheckDigitException();
            }
        }

        if ($this->upca || $this->upce) {
            $code = '0' . $code;
            ++$length;
        }

        if ($this->upce && !isset ($upce_code)) {
            // UPC-A code shall be printed as UPC-E code. Convert UPC-A into UPC-E:
            $tmp = substr($code, 4, 3);
            if (($tmp == '000') OR ($tmp == '100') OR ($tmp == '200')) {
                // manufacturer code ends in 000, 100, or 200
                $upce_code = substr($code, 2, 2) . substr($code, 9, 3) . substr($code, 4, 1);
            } else {
                $tmp = substr($code, 5, 2);
                if ($tmp == '00') {
                    // manufacturer code ends in 00
                    $upce_code = substr($code, 2, 3) . substr($code, 10, 2) . '3';
                } else {
                    $tmp = substr($code, 6, 1);
                    if ($tmp == '0') {
                        // manufacturer code ends in 0
                        $upce_code = substr($code, 2, 4) . substr($code, 11, 1) . '4';
                    } else {
                        // manufacturer code does not end in zero
                        $upce_code = substr($code, 2, 5) . substr($code, 11, 1);
                    }
                }
            }
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

        $upce_parities = [
            [
                '0' => ['B', 'B', 'B', 'A', 'A', 'A'],
                '1' => ['B', 'B', 'A', 'B', 'A', 'A'],
                '2' => ['B', 'B', 'A', 'A', 'B', 'A'],
                '3' => ['B', 'B', 'A', 'A', 'A', 'B'],
                '4' => ['B', 'A', 'B', 'B', 'A', 'A'],
                '5' => ['B', 'A', 'A', 'B', 'B', 'A'],
                '6' => ['B', 'A', 'A', 'A', 'B', 'B'],
                '7' => ['B', 'A', 'B', 'A', 'B', 'A'],
                '8' => ['B', 'A', 'B', 'A', 'A', 'B'],
                '9' => ['B', 'A', 'A', 'B', 'A', 'B'],
            ],
            [
                '0' => ['A', 'A', 'A', 'B', 'B', 'B'],
                '1' => ['A', 'A', 'B', 'A', 'B', 'B'],
                '2' => ['A', 'A', 'B', 'B', 'A', 'B'],
                '3' => ['A', 'A', 'B', 'B', 'B', 'A'],
                '4' => ['A', 'B', 'A', 'A', 'B', 'B'],
                '5' => ['A', 'B', 'B', 'A', 'A', 'B'],
                '6' => ['A', 'B', 'B', 'B', 'A', 'A'],
                '7' => ['A', 'B', 'A', 'B', 'A', 'B'],
                '8' => ['A', 'B', 'A', 'B', 'B', 'A'],
                '9' => ['A', 'B', 'B', 'A', 'B', 'A'],
            ],
        ];

        $seq = '101'; // left guard bar
        if ($this->upce && isset ($upce_code)) {
            $barcode = new Barcode($upce_code);
            $p = $upce_parities[$code[1]][$checksumDigit];
            for ($i = 0; $i < 6; ++$i) {
                $seq .= $codes[$p[$i]][$upce_code[$i]];
            }
            $seq .= '010101'; // right guard bar
        } else {
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
        }

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
