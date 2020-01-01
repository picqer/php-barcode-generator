<?php

namespace Picqer\Barcode\Types;

/*
 * POSTNET and PLANET barcodes.
 * Used by U.S. Postal Service for automated mail sorting
 *
 * @param $code (string) zip code to represent. Must be a string containing a zip code of the form DDDDD or
 *     DDDDD-DDDD.
 * @param $planet (boolean) if true print the PLANET barcode, otherwise print POSTNET
 */

class TypePostnet extends Type
{
    protected $barlen = Array(
        0 => Array(2, 2, 1, 1, 1),
        1 => Array(1, 1, 1, 2, 2),
        2 => Array(1, 1, 2, 1, 2),
        3 => Array(1, 1, 2, 2, 1),
        4 => Array(1, 2, 1, 1, 2),
        5 => Array(1, 2, 1, 2, 1),
        6 => Array(1, 2, 2, 1, 1),
        7 => Array(2, 1, 1, 1, 2),
        8 => Array(2, 1, 1, 2, 1),
        9 => Array(2, 1, 2, 1, 1)
    );

    public function getBarcodeData(string $code): array
    {
        $bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 2, 'bcode' => array());
        $k = 0;
        $code = str_replace('-', '', $code);
        $code = str_replace(' ', '', $code);
        $len = strlen($code);
        // calculate checksum
        $sum = 0;
        for ($i = 0; $i < $len; ++$i) {
            $sum += intval($code[$i]);
        }
        $chkd = ($sum % 10);
        if ($chkd > 0) {
            $chkd = (10 - $chkd);
        }
        $code .= $chkd;
        $len = strlen($code);
        // start bar
        $bararray['bcode'][$k++] = array('t' => 1, 'w' => 1, 'h' => 2, 'p' => 0);
        $bararray['bcode'][$k++] = array('t' => 0, 'w' => 1, 'h' => 2, 'p' => 0);
        $bararray['maxw'] += 2;
        for ($i = 0; $i < $len; ++$i) {
            for ($j = 0; $j < 5; ++$j) {
                $h = $this->barlen[$code[$i]][$j];
                $p = floor(1 / $h);
                $bararray['bcode'][$k++] = array('t' => 1, 'w' => 1, 'h' => $h, 'p' => $p);
                $bararray['bcode'][$k++] = array('t' => 0, 'w' => 1, 'h' => 2, 'p' => 0);
                $bararray['maxw'] += 2;
            }
        }
        // end bar
        $bararray['bcode'][$k++] = array('t' => 1, 'w' => 1, 'h' => 2, 'p' => 0);
        $bararray['maxw'] += 1;

        return $bararray;
    }
}
