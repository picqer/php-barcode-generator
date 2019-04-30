<?php

namespace Picqer\Barcode\Renders;

class Svg extends BarcodeRenderAbstract implements BarcodeRenderInterface
{
    /**
     * Return a SVG string representation of barcode.
     *
     * @param array  $barcodeData barcode data
     * @param int    $widthFactor minimum width of a single bar in user units
     * @param int    $totalHeight height of barcode in user units
     * @param string $color       hexidecimal foreground color (in SVG format) for bar elements (background is transparent)
     *
     * @return string SVG code
     */
    public function render(array $barcodeData, int $widthFactor = 2, int $totalHeight = 30, string $color = '#000000'): string
    {
        // replace table for special characters
        $repstr = array("\0" => '', '&' => '&amp;', '<' => '&lt;', '>' => '&gt;');

        $width = round(($barcodeData['maxWidth'] * $widthFactor), 3);

        $svg = '<?xml version="1.0" standalone="no" ?>'."\n";
        $svg .= '<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">'."\n";
        $svg .= '<svg width="'.$width.'" height="'.$totalHeight.'" viewBox="0 0 '.$width.' '.$totalHeight.'" version="1.1" xmlns="http://www.w3.org/2000/svg">'."\n";
        $svg .= "\t".'<desc>'.strtr($barcodeData['code'], $repstr).'</desc>'."\n";
        $svg .= "\t".'<g id="bars" fill="'.$color.'" stroke="none">'."\n";
        // print bars
        $positionHorizontal = 0;
        foreach ($barcodeData['bars'] as $bar) {
            $barWidth = round(($bar['width'] * $widthFactor), 3);
            $barHeight = round(($bar['height'] * $totalHeight / $barcodeData['maxHeight']), 3);
            if ($bar['drawBar']) {
                $positionVertical = round(($bar['positionVertical'] * $totalHeight / $barcodeData['maxHeight']), 3);
                // draw a vertical bar
                $svg .= "\t\t".'<rect x="'.$positionHorizontal.'" y="'.$positionVertical.'" width="'.$barWidth.'" height="'.$barHeight.'" />'."\n";
            }
            $positionHorizontal += $barWidth;
        }
        $svg .= "\t".'</g>'."\n";
        $svg .= '</svg>'."\n";

        return $svg;
    }
}
