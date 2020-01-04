<?php

namespace Picqer\Barcode\Helpers;

use Picqer\Barcode\Barcode;
use Picqer\Barcode\BarcodeBar;

/**
 * Convert binary barcode sequence string to Barcode representation.
 */
class BinarySequenceConverter
{
    public static function convert(string $code, string $sequence): Barcode
    {
        $barcode = new Barcode($code);

        $len = strlen($sequence);
        $w = 0;
        for ($i = 0; $i < $len; ++$i) {
            $w += 1;
            if (($i == ($len - 1)) OR (($i < ($len - 1)) AND ($sequence[$i] != $sequence[($i + 1)]))) {
                if ($sequence[$i] == '1') {
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
}
