<?php

namespace Picqer\Barcode;

class BarcodeGeneratorPNG extends BarcodeGenerator
{

    /**
     * Return a PNG image representation of barcode (requires GD or Imagick library).
     *
     * @param string $code code to print
     * @param string $type type of barcode:
     * @param int $widthFactor Width of a single bar element in pixels.
     * @param int $totalHeight Height of a single bar element in pixels.
     * @param array $color RGB (0-255) foreground color for bar elements (background is transparent).
     * @return string image data or false in case of error.
     * @public
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
            $png = imagecreate($width, $height);
            $colorBackground = imagecolorallocate($png, 255, 255, 255);
            imagecolortransparent($png, $colorBackground);
            $colorForeground = imagecolorallocate($png, $color[0], $color[1], $color[2]);
        } elseif (extension_loaded('imagick')) {
            $imagick = true;
            $colorForeground = new \imagickpixel('rgb(' . $color[0] . ',' . $color[1] . ',' . $color[2] . ')');
            $png = new \Imagick();
            $png->newImage($width, $height, 'none', 'png');
            $imageMagickObject = new \imagickdraw();
            $imageMagickObject->setFillColor($colorForeground);
        } else {
            return false;
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
                    imagefilledrectangle($png, $positionHorizontal, $y, ($positionHorizontal + $bw) - 1, ($y + $bh),
                        $colorForeground);
                }
            }
            $positionHorizontal += $bw;
        }
        ob_start();
        if ($imagick && isset($imageMagickObject)) {
            $png->drawImage($imageMagickObject);
            echo $png;
        } else {
            imagepng($png);
            imagedestroy($png);
        }
        $image = ob_get_clean();

        return $image;
    }
}