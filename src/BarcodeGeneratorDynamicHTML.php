<?php

namespace Picqer\Barcode;

class BarcodeGeneratorDynamicHTML extends BarcodeGenerator
{
    /**
     * Return an HTML representation of barcode.
     *
     * @param string $barcode code to print
     * @param string $type type of barcode
     * @param string $foregroundColor Foreground color for bar elements as '#333' or 'orange' for example (background is transparent).
     * @return string HTML code.
     */
    public function getBarcode($barcode, $type, string $foregroundColor = 'black')
    {
        $barcodeData = $this->getBarcodeData($barcode, $type);

        $widthFactor = 100 / $barcodeData->getWidth();

        $html = '<div style="font-size:0;position:relative;width:100%;height:100%">' . PHP_EOL;

        $positionHorizontal = 0;
        /** @var BarcodeBar $bar */
        foreach ($barcodeData->getBars() as $bar) {
            $barWidth = $bar->getWidth() * $widthFactor;

            if ($bar->isBar() && $barWidth > 0) {
                // draw a vertical bar
                $html .= '<div style="background-color:' . $foregroundColor . ';width:' . $barWidth . '%;height:100%;position:absolute;left:' . $positionHorizontal . '%;top:0;">&nbsp;</div>' . PHP_EOL;
            }

            $positionHorizontal += $barWidth;
        }

        $html .= '</div>' . PHP_EOL;

        return $html;
    }
}
