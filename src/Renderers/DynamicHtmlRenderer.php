<?php

namespace Picqer\Barcode\Renderers;

use Picqer\Barcode\Barcode;
use Picqer\Barcode\BarcodeBar;

class DynamicHtmlRenderer
{
    protected const WIDTH_PRECISION = 6;

    protected string $foregroundColor = 'black';
    protected ?string $backgroundColor = null;

    public function render(Barcode $barcode): string
    {
        $html = '<div style="font-size:0;position:relative;width:100%;height:100%' . ($this->backgroundColor ? ';background-color:' . $this->backgroundColor : '') . '">' . PHP_EOL;

        $positionHorizontal = 0;
        /** @var BarcodeBar $bar */
        foreach ($barcode->getBars() as $bar) {
            $barWidth = $bar->getWidth() / $barcode->getWidth() * 100;
            $barHeight = round(($bar->getHeight() / $barcode->getHeight() * 100), 3);

            if ($bar->isBar() && $barWidth > 0) {
                $positionVertical = round(($bar->getPositionVertical() / $barcode->getHeight() * 100), 3);

                // draw a vertical bar
                $html .= '<div style="background-color:' . $this->foregroundColor . ';width:' . round($barWidth, self::WIDTH_PRECISION) . '%;height:' . $barHeight . '%;position:absolute;left:' . round($positionHorizontal, self::WIDTH_PRECISION) . '%;top:' . $positionVertical . (($positionVertical > 0) ? '%' : '') . '">&nbsp;</div>' . PHP_EOL;
            }

            $positionHorizontal += $barWidth;
        }

        $html .= '</div>' . PHP_EOL;

        return $html;
    }

    // Use HTML color definitions, like 'red' or '#ff0000'
    public function setForegroundColor(string $color): self
    {
        $this->foregroundColor = $color;
        return $this;
    }

    // Use HTML color definitions, like 'red' or '#ff0000'
    public function setBackgroundColor(?string $color): self
    {
        $this->backgroundColor = $color;
        return $this;
    }
}
