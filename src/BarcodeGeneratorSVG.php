<?php

namespace Picqer\Barcode;

class BarcodeGeneratorSVG extends BarcodeGenerator
{
    /**
     * Return a SVG string representation of barcode.
     *
     * @param $barcode (string) code to print
     * @param BarcodeGenerator::TYPE_* $type (string) type of barcode
     * @param $widthFactor (float) Minimum width of a single bar in user units.
     * @param $height (float) Height of barcode in user units.
     * @param $foregroundColor (string) Foreground color (in SVG format) for bar elements (background is transparent).
     * @return string SVG code.
     * @public
     */
    public function getBarcode(string $barcode, $type, float $widthFactor = 2, float $height = 30, string $foregroundColor = 'black'): string
    {
        $barcodeData = $this->getBarcodeData($barcode, $type);

        $width = round(($barcodeData->getWidth() * $widthFactor), 3);

        $renderer = new \Picqer\Barcode\Renderers\SvgRenderer();
        $renderer->setForegroundColor($foregroundColor);

        return $renderer->render($barcodeData, $width, $height);
    }
}
