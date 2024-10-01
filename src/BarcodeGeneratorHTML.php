<?php

namespace Picqer\Barcode;

use Picqer\Barcode\Exceptions\UnknownTypeException;
use Picqer\Barcode\Helpers\ColorHelper;

class BarcodeGeneratorHTML extends BarcodeGenerator
{
    /**
     * Return an HTML representation of barcode.
     * This original version uses pixel based widths and heights. Use Dynamic HTML version for better quality representation.
     *
     * @param string $barcode code to print
     * @param BarcodeGenerator::TYPE_* $type (string) type of barcode
     * @param int $widthFactor Width of a single bar element in pixels.
     * @param int $height Height of a single bar element in pixels.
     * @param string $foregroundColor Foreground color for bar elements as '#333' or 'orange' for example (background is transparent).
     * @return string HTML code.
     * @throws UnknownTypeException
     */
    public function getBarcode(string $barcode, $type, int $widthFactor = 2, int $height = 30, string $foregroundColor = 'black'): string
    {
        $barcodeData = $this->getBarcodeData($barcode, $type);

        $width = round(($barcodeData->getWidth() * $widthFactor), 3);

        $renderer = new \Picqer\Barcode\Renderers\HtmlRenderer();
        $renderer->setForegroundColor(ColorHelper::getArrayFromColorString($foregroundColor));

        return $renderer->render($barcodeData, $width, $height);
    }
}
