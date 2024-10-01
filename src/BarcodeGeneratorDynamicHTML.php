<?php

namespace Picqer\Barcode;

use Picqer\Barcode\Exceptions\UnknownTypeException;
use Picqer\Barcode\Helpers\ColorHelper;

class BarcodeGeneratorDynamicHTML extends BarcodeGenerator
{
    /**
     * Return an HTML representation of barcode.
     * This 'dynamic' version uses percentage based widths and heights, resulting in a vector-y qualitative result.
     *
     * @param string $barcode code to print
     * @param BarcodeGenerator::TYPE_* $type (string) type of barcode
     * @param string $foregroundColor Foreground color for bar elements as '#333' or 'orange' for example (background is transparent).
     * @return string HTML code.
     * @throws UnknownTypeException
     */
    public function getBarcode(string $barcode, $type, string $foregroundColor = 'black'): string
    {
        $barcodeData = $this->getBarcodeData($barcode, $type);

        $renderer = new \Picqer\Barcode\Renderers\DynamicHtmlRenderer();
        $renderer->setForegroundColor(ColorHelper::getArrayFromColorString($foregroundColor));

        return $renderer->render($barcodeData);
    }
}
