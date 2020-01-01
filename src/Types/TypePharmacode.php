<?php

namespace Picqer\Barcode\Types;

use Picqer\Barcode\Helpers\BinarySequenceConverter;

/*
 * Pharmacode
 * Contains digits (0 to 9)
 */

class TypePharmacode implements TypeInterface
{
    public function getBarcodeData(string $code): array
    {
        $seq = '';
        $code = intval($code);
        while ($code > 0) {
            if (($code % 2) == 0) {
                $seq .= '11100';
                $code -= 2;
            } else {
                $seq .= '100';
                $code -= 1;
            }
            $code /= 2;
        }
        $seq = substr($seq, 0, -2);
        $seq = strrev($seq);
        $bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());

        return BinarySequenceConverter::convert($seq, $bararray);
    }
}
