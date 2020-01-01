<?php

namespace Picqer\Barcode\Types;

/*
 * Pharmacode two-track
 * Contains digits (0 to 9)
 */

class TypePharmacodeTwoCode implements TypeInterface
{
    public function getBarcodeData(string $code): array
    {
        $seq = '';
        $code = intval($code);

        do {
            switch ($code % 3) {
                case 0:
                    $seq .= '3';
                    $code = ($code - 3) / 3;
                    break;

                case 1:
                    $seq .= '1';
                    $code = ($code - 1) / 3;
                    break;

                case 2:
                    $seq .= '2';
                    $code = ($code - 2) / 3;
                    break;
            }
        } while ($code != 0);

        $seq = strrev($seq);
        $k = 0;
        $bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 2, 'bcode' => array());
        $len = strlen($seq);

        for ($i = 0; $i < $len; ++$i) {
            switch ($seq[$i]) {
                case '1':
                    $p = 1;
                    $h = 1;
                    break;

                case '2':
                    $p = 0;
                    $h = 1;
                    break;

                case '3':
                    $p = 0;
                    $h = 2;
                    break;
            }

            $bararray['bcode'][$k++] = array('t' => 1, 'w' => 1, 'h' => $h, 'p' => $p);
            $bararray['bcode'][$k++] = array('t' => 0, 'w' => 1, 'h' => 2, 'p' => 0);
            $bararray['maxw'] += 2;
        }

        unset($bararray['bcode'][($k - 1)]);
        --$bararray['maxw'];

        return $bararray;
    }
}
