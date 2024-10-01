<?php

namespace Picqer\Barcode;

use Picqer\Barcode\Exceptions\UnknownTypeException;

class BarcodeGeneratorJPG extends BarcodeGenerator
{
    protected ?bool $useImagick = null;

    /**
     * Return a PNG image representation of barcode (requires GD or Imagick library).
     *
     * @param string $barcode code to print
     * @param BarcodeGenerator::TYPE_* $type (string) type of barcode
     * @param int $widthFactor Width of a single bar element in pixels.
     * @param int $height Height of a single bar element in pixels.
     * @param array $foregroundColor RGB (0-255) foreground color for bar elements (background is transparent).
     * @return string image data or false in case of error.
     * @throws UnknownTypeException
     */
    public function getBarcode(string $barcode, $type, int $widthFactor = 2, int $height = 30, array $foregroundColor = [0, 0, 0]): string
    {
        $barcodeData = $this->getBarcodeData($barcode, $type);

        $renderer = new \Picqer\Barcode\Renderers\JpgRenderer();
        $renderer->setForegroundColor($foregroundColor);

        if (! is_null($this->useImagick)) {
            if ($this->useImagick) {
                $renderer->useImagick();
            } else {
                $renderer->useGd();
            }
        }

        return $renderer->render($barcodeData, $barcodeData->getWidth() * $widthFactor, $height);
    }

    /**
     * Force the use of Imagick image extension
     */
    public function useImagick(): void
    {
        $this->useImagick = true;
    }

    /**
     * Force the use of the GD image library
     */
    public function useGd(): void
    {
        $this->useImagick = false;
    }
}
