<?php

namespace Picqer\Barcode\Renderers;

use Imagick;
use ImagickPixel;

class JpgRenderer extends PngRenderer
{
    protected function createImagickImageObject(int $width, int $height): Imagick
    {
        $image = new Imagick();
        if ($this->backgroundColor !== null) {
            // Colored background
            $backgroundColor = new ImagickPixel('rgb(' . implode(',', $this->backgroundColor) . ')');
        } else {
            // Use transparent background
            $backgroundColor = new ImagickPixel('none');
        }
        $image->newImage($width, $height, $backgroundColor, 'JPG');

        return $image;
    }

    protected function generateGdImage($image): void
    {
        \imagejpeg($image);
        \imagedestroy($image);
    }
}
