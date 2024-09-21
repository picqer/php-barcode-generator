<?php

namespace Picqer\Barcode\Renderers;

use Picqer\Barcode\Barcode;
use Picqer\Barcode\BarcodeBar;

class HtmlRenderer
{
    protected string $foregroundColor = 'black';
    protected ?string $backgroundColor = null;

    public function render(Barcode $barcode, float $width = 200, float $height = 30): string
    {
        $widthFactor = $width / $barcode->getWidth();

        $html = '<div style="font-size:0;position:relative;width:' . $width . 'px;height:' . ($height) . 'px;' . ($this->backgroundColor ? 'background-color:' . $this->backgroundColor . ';' : '') . '">' . PHP_EOL;

        $positionHorizontal = 0;
        /** @var BarcodeBar $bar */
        foreach ($barcode->getBars() as $bar) {
            $barWidth = round(($bar->getWidth() * $widthFactor), 3);
            $barHeight = round(($bar->getHeight() * $height / $barcode->getHeight()), 3);

            if ($bar->isBar() && $barWidth > 0) {
                $positionVertical = round(($bar->getPositionVertical() * $height / $barcode->getHeight()), 3);

                // draw a vertical bar
                $html .= '<div style="background-color:' . $this->foregroundColor . ';width:' . $barWidth . 'px;height:' . $barHeight . 'px;position:absolute;left:' . $positionHorizontal . 'px;top:' . $positionVertical . (($positionVertical > 0) ? 'px' : '') . '">&nbsp;</div>' . PHP_EOL;
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
