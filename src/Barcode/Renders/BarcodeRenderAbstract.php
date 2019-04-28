<?php

namespace Picqer\Barcode\Renders;

use Picqer\Barcode\Exceptions\InvalidHexidecimalException;

abstract class BarcodeRenderAbstract
{
    /**
     * Converts a Hexidecimal string to an RGB array.
     *
     * @param string $hex hexidecimal value to convert to RGB array
     *
     * @return array
     *
     * @throws InvalideHexidecimalException
     */
    public function convertHexToRGB(string $hex): array
    {
        $hex = str_replace('#', '', $hex);

        if (3 == strlen($hex)) {
            $r = hexdec(substr($hex, 0, 1).substr($hex, 0, 1));
            $g = hexdec(substr($hex, 1, 1).substr($hex, 1, 1));
            $b = hexdec(substr($hex, 2, 1).substr($hex, 2, 1));

            return [$r, $g, $b];
        } elseif (6 == strlen($hex)) {
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));

            return [$r, $g, $b];
        }

        throw new InvalidHexidecimalException();
    }
}
