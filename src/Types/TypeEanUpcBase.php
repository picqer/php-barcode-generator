<?php

namespace Picqer\Barcode\Types;

use Picqer\Barcode\Exceptions\InvalidCharacterException;
use Picqer\Barcode\Exceptions\InvalidCheckDigitException;

/*
 * EAN13 and UPC-A barcodes.
 * EAN13: European Article Numbering international retail product code
 * UPC-A: Universal product code seen on almost all retail products in the USA and Canada
 * UPC-E: Short version of UPC symbol
 *
 * @param $code (string) code to represent.
 * @param $len (string) barcode type: 6 = UPC-E, 8 = EAN8, 13 = EAN13, 12 = UPC-A
 */

abstract class TypeEanUpcBase extends Type
{
    protected $length = 13;
    protected $upca = false;
    protected $upce = false;

    public function getBarcodeData(string $code): array
    {
        $length = $this->length;

        $data_len = $length - 1;
        //Padding
        $code = str_pad($code, $data_len, '0', STR_PAD_LEFT);
        $code_len = strlen($code);
        // calculate check digit
        $sum_a = 0;
        for ($i = 1; $i < $data_len; $i += 2) {
            $sum_a += $code[$i];
        }
        if ($length > 12) {
            $sum_a *= 3;
        }
        $sum_b = 0;
        for ($i = 0; $i < $data_len; $i += 2) {
            $sum_b += intval(($code[$i]));
        }
        if ($length < 13) {
            $sum_b *= 3;
        }
        $r = ($sum_a + $sum_b) % 10;
        if ($r > 0) {
            $r = (10 - $r);
        }
        if ($code_len == $data_len) {
            // add check digit
            $code .= $r;
        } elseif ($r !== intval($code[$data_len])) {
            throw new InvalidCheckDigitException();
        }
        if ($this->upca || $this->upce) {
            // UPC-A
            $code = '0' . $code;
            ++$length;
        }
        if ($this->upce) {
            // convert UPC-A to UPC-E
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
        //Convert digits to bars
        $codes = array(
            'A' => array( // left odd parity
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
            ),
            'B' => array( // left even parity
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
            ),
            'C' => array( // right
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
            )
        );
        $parities = array(
            '0' => array('A', 'A', 'A', 'A', 'A', 'A'),
            '1' => array('A', 'A', 'B', 'A', 'B', 'B'),
            '2' => array('A', 'A', 'B', 'B', 'A', 'B'),
            '3' => array('A', 'A', 'B', 'B', 'B', 'A'),
            '4' => array('A', 'B', 'A', 'A', 'B', 'B'),
            '5' => array('A', 'B', 'B', 'A', 'A', 'B'),
            '6' => array('A', 'B', 'B', 'B', 'A', 'A'),
            '7' => array('A', 'B', 'A', 'B', 'A', 'B'),
            '8' => array('A', 'B', 'A', 'B', 'B', 'A'),
            '9' => array('A', 'B', 'B', 'A', 'B', 'A')
        );
        $upce_parities = array();
        $upce_parities[0] = array(
            '0' => array('B', 'B', 'B', 'A', 'A', 'A'),
            '1' => array('B', 'B', 'A', 'B', 'A', 'A'),
            '2' => array('B', 'B', 'A', 'A', 'B', 'A'),
            '3' => array('B', 'B', 'A', 'A', 'A', 'B'),
            '4' => array('B', 'A', 'B', 'B', 'A', 'A'),
            '5' => array('B', 'A', 'A', 'B', 'B', 'A'),
            '6' => array('B', 'A', 'A', 'A', 'B', 'B'),
            '7' => array('B', 'A', 'B', 'A', 'B', 'A'),
            '8' => array('B', 'A', 'B', 'A', 'A', 'B'),
            '9' => array('B', 'A', 'A', 'B', 'A', 'B')
        );
        $upce_parities[1] = array(
            '0' => array('A', 'A', 'A', 'B', 'B', 'B'),
            '1' => array('A', 'A', 'B', 'A', 'B', 'B'),
            '2' => array('A', 'A', 'B', 'B', 'A', 'B'),
            '3' => array('A', 'A', 'B', 'B', 'B', 'A'),
            '4' => array('A', 'B', 'A', 'A', 'B', 'B'),
            '5' => array('A', 'B', 'B', 'A', 'A', 'B'),
            '6' => array('A', 'B', 'B', 'B', 'A', 'A'),
            '7' => array('A', 'B', 'A', 'B', 'A', 'B'),
            '8' => array('A', 'B', 'A', 'B', 'B', 'A'),
            '9' => array('A', 'B', 'B', 'A', 'B', 'A')
        );
        $k = 0;
        $seq = '101'; // left guard bar
        if ($this->upce) {
            $bararray = array('code' => $upce_code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());
            $p = $upce_parities[$code[1]][$r];
            for ($i = 0; $i < 6; ++$i) {
                $seq .= $codes[$p[$i]][$upce_code[$i]];
            }
            $seq .= '010101'; // right guard bar
        } else {
            $bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());
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
                if ( ! isset($codes['C'][$code[$i]])) {
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
                $bararray['bcode'][$k] = array('t' => $t, 'w' => $w, 'h' => 1, 'p' => 0);
                $bararray['maxw'] += $w;
                ++$k;
                $w = 0;
            }
        }

        return $bararray;
    }
}
