<?php

namespace Picqer\Barcode;

use Picqer\Barcode\Exceptions\BarcodeException;

class BarcodeGeneratorJPG extends BarcodeGenerator
{

    /**
     * Return a JPG image representation of barcode (requires GD or Imagick library).
     *
     * @param string $code code to print
     * @param string $type type of barcode:
     * @param int $widthFactor Width of a single bar element in pixels.
     * @param int $totalHeight Height of a single bar element in pixels.
     * @param array $color RGB (0-255) foreground color for bar elements (background is transparent).
     * @return string image data or false in case of error.
     * @public
     * @throws BarcodeException
     */
    public function getBarcode($code, $type, $widthFactor = 2, $totalHeight = 30, $color = array(0, 0, 0))
    {
        $barcodeData = $this->getBarcodeData($code, $type);

        // calculate image size
        $width = ($barcodeData['maxWidth'] * $widthFactor);
        $height = $totalHeight;

        if (function_exists('imagecreate')) {
            // GD library
            $imagick = false;
            $jpg = imagecreate($width, $height);
            $colorBackground = imagecolorallocate($jpg, 255, 255, 255);
            imagecolortransparent($jpg, $colorBackground);
            $colorForeground = imagecolorallocate($jpg, $color[0], $color[1], $color[2]);
        } elseif (extension_loaded('imagick')) {
            $imagick = true;
            $colorForeground = new \imagickpixel('rgb(' . $color[0] . ',' . $color[1] . ',' . $color[2] . ')');
            $jpg = new \Imagick();
            $jpg->newImage($width, $height, 'none', 'jpg');
            $imageMagickObject = new \imagickdraw();
            $imageMagickObject->setFillColor($colorForeground);
        } else {
            throw new BarcodeException('Neither gd-lib or imagick are installed!');
        }

        // print bars
        $positionHorizontal = 0;
        foreach ($barcodeData['bars'] as $bar) {
            $bw = round(($bar['width'] * $widthFactor), 3);
            $bh = round(($bar['height'] * $totalHeight / $barcodeData['maxHeight']), 3);
            if ($bar['drawBar']) {
                $y = round(($bar['positionVertical'] * $totalHeight / $barcodeData['maxHeight']), 3);
                // draw a vertical bar
                if ($imagick && isset($imageMagickObject)) {
                    $imageMagickObject->rectangle($positionHorizontal, $y, ($positionHorizontal + $bw), ($y + $bh));
                } else {
                    imagefilledrectangle($jpg, $positionHorizontal, $y, ($positionHorizontal + $bw) - 1, ($y + $bh),
                        $colorForeground);
                }
            }
            $positionHorizontal += $bw;
        }
        ob_start();
        if ($imagick && isset($imageMagickObject)) {
            $jpg->drawImage($imageMagickObject);
            echo $jpg;
        } else {
            imagejpeg($jpg);
            imagedestroy($jpg);
        }
        $image = ob_get_clean();

        return $image;
    }
}