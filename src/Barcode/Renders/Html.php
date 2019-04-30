<?php

namespace Picqer\Barcode\Renders;

class Html extends BarcodeRenderAbstract implements BarcodeRenderInterface
{
    /**
     * Return an HTML representation of barcode.
     *
     * @param array  $barcodeData barcode data
     * @param int    $widthFactor width of a single bar element in pixels
     * @param int    $totalHeight height of a single bar element in pixels
     * @param string $color       foreground color for bar elements (background is transparent)
     *
     * @return string HTML code
     */
    public function render(array $barcodeData, int $widthFactor = 2, int $totalHeight = 30, string $color = '#000000'): string
    {
        $html = '<div style="font-size:0;position:relative;width:'.($barcodeData['maxWidth'] * $widthFactor).'px;height:'.($totalHeight).'px;">'."\n";

        $positionHorizontal = 0;
        foreach ($barcodeData['bars'] as $bar) {
            $barWidth = round(($bar['width'] * $widthFactor), 3);
            $barHeight = round(($bar['height'] * $totalHeight / $barcodeData['maxHeight']), 3);

            if ($bar['drawBar']) {
                $positionVertical = round(($bar['positionVertical'] * $totalHeight / $barcodeData['maxHeight']), 3);
                // draw a vertical bar
                $html .= '<div style="background-color:'.$color.';width:'.$barWidth.'px;height:'.$barHeight.'px;position:absolute;left:'.$positionHorizontal.'px;top:'.$positionVertical.'px;">&nbsp;</div>'."\n";
            }

            $positionHorizontal += $barWidth;
        }

        $html .= '</div>'."\n";

        return $html;
    }
}
