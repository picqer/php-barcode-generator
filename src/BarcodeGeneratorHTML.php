<?php

namespace Picqer\Barcode;

class BarcodeGeneratorHTML extends BarcodeGenerator
{
    /**
     * Return an HTML representation of barcode.
     *
     * @param string $barcode code to print
     * @param string $type type of barcode
     * @param int $widthFactor Width of a single bar element in pixels.
     * @param int $height Height of a single bar element in pixels.
     * @param string $foregroundColor Foreground color for bar elements as '#333' or 'orange' for example (background is transparent).
     * @return string HTML code.
     */
    public function getBarcode($barcode, $type, int $widthFactor = 2, int $height = 30, string $foregroundColor = 'black')
    {
        $barcodeData = $this->getBarcodeData($barcode, $type);

        $html = '<div style="font-size:0;position:relative;width:' . ($barcodeData['maxWidth'] * $widthFactor) . 'px;height:' . ($height) . 'px;">' . PHP_EOL;

        $positionHorizontal = 0;
        foreach ($barcodeData['bars'] as $bar) {
            $barWidth = round(($bar['width'] * $widthFactor), 3);
            $barHeight = round(($bar['height'] * $height / $barcodeData['maxHeight']), 3);

            if ($bar['drawBar'] && $barWidth > 0) {
                $positionVertical = round(($bar['positionVertical'] * $height / $barcodeData['maxHeight']), 3);
                // draw a vertical bar
                $html .= '<div style="background-color:' . $foregroundColor . ';width:' . $barWidth . 'px;height:' . $barHeight . 'px;position:absolute;left:' . $positionHorizontal . 'px;top:' . $positionVertical . 'px;">&nbsp;</div>' . PHP_EOL;
            }

            $positionHorizontal += $barWidth;
        }

        $html .= '</div>' . PHP_EOL;

        return $html;
    }
}