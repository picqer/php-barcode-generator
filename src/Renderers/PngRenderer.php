<?php

namespace Picqer\Barcode\Renderers;

use Imagick;
use ImagickDraw;
use ImagickPixel;
use Picqer\Barcode\Barcode;
use Picqer\Barcode\BarcodeBar;
use Picqer\Barcode\Exceptions\BarcodeException;

class PngRenderer implements RendererInterface
{
    protected array $foregroundColor = [0, 0, 0];
    protected ?array $backgroundColor = null;

    protected bool $useImagick;

    /**
     * @throws BarcodeException
     */
    public function __construct()
    {
        // Auto switch between GD and Imagick based on what is installed
        if (extension_loaded('imagick')) {
            $this->useImagick = true;
        } elseif (function_exists('imagecreate')) {
            $this->useImagick = false;
        } else {
            throw new BarcodeException('Neither gd-lib or imagick are installed!');
        }
    }

    /**
     * Force the use of Imagick image extension
     */
    public function useImagick(): self
    {
        $this->useImagick = true;
        return $this;
    }

    /**
     * Force the use of the GD image library
     */
    public function useGd(): self
    {
        $this->useImagick = false;
        return $this;
    }

    // Floats in width and height will be rounded to integers
    // For best (and valid) result, use a width as a factor of the width of the Barcode object
    // Example: $width = $barcode->getWidth() * 3
    public function render(Barcode $barcode, float $width = 200, float $height = 30): string
    {
        $width = (int)round($width);
        $height = (int)round($height);

        $widthFactor = $width / $barcode->getWidth();

        if ($this->useImagick) {
            $image = $this->createImagickImageObject($width, $height);
            $imagickBarsShape = new ImagickDraw();
            $imagickBarsShape->setFillColor(new ImagickPixel('rgb(' . implode(',', $this->foregroundColor) .')'));
        } else {
            $image = $this->createGdImageObject($width, $height);
            $gdForegroundColor = \imagecolorallocate($image, $this->foregroundColor[0], $this->foregroundColor[1], $this->foregroundColor[2]);
        }

        // print bars
        $positionHorizontal = 0;
        /** @var BarcodeBar $bar */
        foreach ($barcode->getBars() as $bar) {
            $barWidth = $bar->getWidth() * $widthFactor;

            if ($bar->isBar() && $barWidth > 0) {
                $y = (int)round(($bar->getPositionVertical() * $height / $barcode->getHeight()));
                $barHeight = (int)round(($bar->getHeight() * $height / $barcode->getHeight()));

                // draw a vertical bar
                if ($this->useImagick) {
                    $imagickBarsShape->rectangle((int)round($positionHorizontal), $y, (int)round($positionHorizontal + $barWidth - 1), ($y + $barHeight));
                } else {
                    \imagefilledrectangle($image, (int)round($positionHorizontal), $y, (int)round($positionHorizontal + $barWidth - 1), ($y + $barHeight), $gdForegroundColor);
                }
            }
            $positionHorizontal += $barWidth;
        }

        if ($this->useImagick) {
            $image->drawImage($imagickBarsShape);
            return $image->getImageBlob();
        } else {
            ob_start();
            $this->generateGdImage($image);
            return ob_get_clean();
        }
    }

    // Use RGB color definitions, like [0, 0, 0] or [255, 255, 255]
    public function setForegroundColor(array $color): self
    {
        $this->foregroundColor = $color;
        return $this;
    }

    // Use RGB color definitions, like [0, 0, 0] or [255, 255, 255]
    // If no color is set, the background will be transparent
    public function setBackgroundColor(?array $color): self
    {
        $this->backgroundColor = $color;
        return $this;
    }

    protected function createGdImageObject(int $width, int $height)
    {
        $image = \imagecreate($width, $height);

        if ($this->backgroundColor !== null) {
            // Colored background
            $backgroundColor = \imagecolorallocate($image, $this->backgroundColor[0], $this->backgroundColor[1], $this->backgroundColor[2]);
            \imagefill($image, 0, 0, $backgroundColor);
        } else {
            // Use transparent background
            $backgroundColor = \imagecolorallocate($image, 255, 255, 255);
            \imagecolortransparent($image, $backgroundColor);
        }

        return $image;
    }

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
        $image->newImage($width, $height, $backgroundColor, 'PNG');

        return $image;
    }

    protected function generateGdImage($image): void
    {
        \imagepng($image);
        \imagedestroy($image);
    }
}
