<?php

namespace Picqer\Barcode\Types;

use Picqer\Barcode\Barcode;
use Picqer\Barcode\BarcodeBar;
use Picqer\Barcode\Exceptions\InvalidCharacterException;

/*
 * Interleaved 2 of 5 barcodes.
 * Compact numeric code, widely used in industry, air cargo
 * Contains digits (0 to 9) and encodes the data in the width of both bars and spaces.
 */

class TypeFebraban implements TypeInterface
{
    public function getBarcodeData(string $code): Barcode
    {
        
        $c = $code;
        $c = str_replace(".", "", $c);
        
        $c = str_replace(" ", "", $c);
        
        $f = $c[0].$c[1].$c[2].$c[3].
        $c[32].$c[33].$c[34].$c[35].$c[36].$c[37].$c[38].$c[39].$c[40].$c[41].$c[42].$c[43].$c[44].$c[45].$c[46].
        $c[4].$c[5].$c[6].$c[7].$c[8].
        $c[10].$c[11].$c[12].$c[13].$c[14].$c[15].$c[16].$c[17].$c[18].$c[19].
        $c[21].$c[22].$c[23].$c[24].$c[25].$c[26].$c[27].$c[28].$c[29].$c[30];
        $code = $f;
        unset($f);
        //die($code);
        
        $chr['0'] = '11221';
        $chr['1'] = '21112';
        $chr['2'] = '12112';
        $chr['3'] = '22111';
        $chr['4'] = '11212';
        $chr['5'] = '21211';
        $chr['6'] = '12211';
        $chr['7'] = '11122';
        $chr['8'] = '21121';
        $chr['9'] = '12121';
        $chr['A'] = '11';
        $chr['Z'] = '21';

        // add checksum
        $code .= $this->getChecksum($code);

        if ((strlen($code) % 2) != 0) {
            // add leading zero if code-length is odd
            $code = '0' . $code;
        }
        // add start and stop codes
        $code = 'AA' . strtolower($code) . 'ZA';

        $barcode = new Barcode($code);
        for ($i = 0; $i < strlen($code); $i = ($i + 2)) {
            $char_bar = $code[$i];
            $char_space = $code[$i + 1];
            if (! isset($chr[$char_bar]) || ! isset($chr[$char_space])) {
                throw new InvalidCharacterException();
            }

            // create a bar-space sequence
            $seq = '';
            $chrlen = strlen($chr[$char_bar]);
            for ($s = 0; $s < $chrlen; $s++) {
                $seq .= $chr[$char_bar][$s] . $chr[$char_space][$s];
            }

            for ($j = 0; $j < strlen($seq); ++$j) {
                if (($j % 2) == 0) {
                    $t = true; // bar
                } else {
                    $t = false; // space
                }
                $w = $seq[$j];
                $barcode->addBar(new BarcodeBar($w, 1, $t));
            }
        }

        return $barcode;
    }

    protected function getChecksum($code = "123"): string
    {

        return '';
    }
}
