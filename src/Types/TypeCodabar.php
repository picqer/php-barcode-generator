<?php

namespace Picqer\Barcode\Types;

use Picqer\Barcode\Exceptions\InvalidCharacterException;

/*
 * CODABAR barcodes.
 * Older code often used in library systems, sometimes in blood banks
 */

class TypeCodabar implements TypeInterface
{
    public function getBarcodeData(string $code): array
    {
        $chr = array(
            '0' => '11111221',
            '1' => '11112211',
            '2' => '11121121',
            '3' => '22111111',
            '4' => '11211211',
            '5' => '21111211',
            '6' => '12111121',
            '7' => '12112111',
            '8' => '12211111',
            '9' => '21121111',
            '-' => '11122111',
            '$' => '11221111',
            ':' => '21112121',
            '/' => '21211121',
            '.' => '21212111',
            '+' => '11222221',
            'A' => '11221211',
            'B' => '12121121',
            'C' => '11121221',
            'D' => '11122211'
        );
        $bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());
        $k = 0;
        $w = 0;
        $seq = '';
        $code = 'A' . strtoupper($code) . 'A';
        $len = strlen($code);
        for ($i = 0; $i < $len; ++$i) {
            if (! isset($chr[$code[$i]])) {
                throw new InvalidCharacterException('Char ' . $code[$i] . ' is unsupported');
            }
            $seq = $chr[$code[$i]];
            for ($j = 0; $j < 8; ++$j) {
                if (($j % 2) == 0) {
                    $t = true; // bar
                } else {
                    $t = false; // space
                }
                $w = $seq[$j];
                $bararray['bcode'][$k] = array('t' => $t, 'w' => $w, 'h' => 1, 'p' => 0);
                $bararray['maxw'] += $w;
                ++$k;
            }
        }

        return $bararray;
    }
}
