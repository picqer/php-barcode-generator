<?php

namespace Picqer\Barcode\Helpers;

/**
 * Convert binary barcode sequence to barcode array.
 */
class BinarySequenceConverter
{
    public static function convert(string $seq, array $bararray): array
    {
        $len = strlen($seq);
        $w = 0;
        $k = 0;
        for ($i = 0; $i < $len; ++$i) {
            $w += 1;
            if (($i == ($len - 1)) OR (($i < ($len - 1)) AND ($seq[$i] != $seq[($i + 1)]))) {
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
