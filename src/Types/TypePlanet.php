<?php

namespace Picqer\Barcode\Types;

/*
 * PLANET barcodes.
 * Used by U.S. Postal Service for automated mail sorting
 *
 * @param $code (string) zip code to represent. Must be a string containing a zip code of the form DDDDD or
 *     DDDDD-DDDD.
 * @param $planet (boolean) if true print the PLANET barcode, otherwise print POSTNET
 */

class TypePlanet extends TypePostnet
{
    protected $barlen = Array(
        0 => Array(1, 1, 2, 2, 2),
        1 => Array(2, 2, 2, 1, 1),
        2 => Array(2, 2, 1, 2, 1),
        3 => Array(2, 2, 1, 1, 2),
        4 => Array(2, 1, 2, 2, 1),
        5 => Array(2, 1, 2, 1, 2),
        6 => Array(2, 1, 1, 2, 2),
        7 => Array(1, 2, 2, 2, 1),
        8 => Array(1, 2, 2, 1, 2),
        9 => Array(1, 2, 1, 2, 2)
    );
}
