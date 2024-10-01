<?php

namespace Picqer\Barcode\Renderers;

use Picqer\Barcode\Barcode;
use Picqer\Barcode\BarcodeBar;

class DynamicHtmlRenderer implements RendererInterface
{
    protected const WIDTH_PRECISION = 6;

    protected array $foregroundColor = [0, 0, 0];
    protected ?array $backgroundColor = null;

    // Width and height are ignored in this renderer
    public function render(Barcode $barcode, float $width = 200, float $height = 30): string
    {
        $html = '<div style="font-size:0;position:relative;width:100%;height:100%' . ($this->backgroundColor ? ';background-color:rgb(' . implode(',', $this->backgroundColor) . ')' : '') . '">' . PHP_EOL;

        $positionHorizontal = 0;
        /** @var BarcodeBar $bar */
        foreach ($barcode->getBars() as $bar) {
            $barWidth = $bar->getWidth() / $barcode->getWidth() * 100;
            $barHeight = round(($bar->getHeight() / $barcode->getHeight() * 100), 3);

            if ($bar->isBar() && $barWidth > 0) {
                $positionVertical = round(($bar->getPositionVertical() / $barcode->getHeight() * 100), 3);

                // draw a vertical bar
                $html .= '<div style="background-color:rgb(' . implode(',', $this->foregroundColor) . ');width:' . round($barWidth, self::WIDTH_PRECISION) . '%;height:' . $barHeight . '%;position:absolute;left:' . round($positionHorizontal, self::WIDTH_PRECISION) . '%;top:' . $positionVertical . (($positionVertical > 0) ? '%' : '') . '">&nbsp;</div>' . PHP_EOL;
            }

            $positionHorizontal += $barWidth;
        }

        $html .= '</div>' . PHP_EOL;

        return $html;
    }

    public function setForegroundColor(array $color): self
    {
        $this->foregroundColor = $color;
        return $this;
    }

    public function setBackgroundColor(?array $color): self
    {
        $this->backgroundColor = $color;
        return $this;
    }
}
