<?php

namespace Picqer\Barcode\Helpers;

use Picqer\Barcode\Exceptions\UnknownColorException;

class ColorHelper
{
    // Convert textual color values, to array of 3 colors 0-255
    // Can be "red", "#333" or "#009945" styles
    public static function getArrayFromColorString(string $color): array {
        if ($color == 'black') {
            return [0, 0, 0];
        } elseif ($color == 'white') {
            return [255, 255, 255];
        } elseif ($color == 'red') {
            return [255, 0, 0];
        } elseif ($color == 'green') {
            return [0, 255, 0];
        } elseif ($color == 'blue') {
            return [0, 0, 255];
        } elseif ($color == 'yellow') {
            return [255, 255, 0];
        }

        // #333 style
        if (str_starts_with($color, '#') && strlen($color) === 4) {
            return [
                hexdec(substr($color, 1, 1) . substr($color, 1, 1)),
                hexdec(substr($color, 2, 1) . substr($color, 2, 1)),
                hexdec(substr($color, 3, 1) . substr($color, 3, 1)),
            ];
        }

        // #009933 style
        if (str_starts_with($color, '#') && strlen($color) === 7) {
            return [
                hexdec(substr($color, 1, 2)),
                hexdec(substr($color, 2, 2)),
                hexdec(substr($color, 3, 2)),
            ];
        }

        throw new UnknownColorException('Only basic string-based colors are supported in v3.');
    }
}
