<?php

namespace Picqer\Barcode\Renderers;

use Picqer\Barcode\Barcode;
use Picqer\Barcode\BarcodeBar;

class HtmlRenderer implements RendererInterface
{
    protected array $foregroundColor = [0, 0, 0];
    protected ?array $backgroundColor = null;

    public function render(Barcode $barcode, float $width = 200, float $height = 30): string
    {
        $widthFactor = $width / $barcode->getWidth();

        $html = '<div style="font-size:0;position:relative;width:' . $width . 'px;height:' . ($height) . 'px;' . ($this->backgroundColor ? 'background-color:rgb(' . implode(',', $this->backgroundColor) . ');' : '') . '">' . PHP_EOL;

        $positionHorizontal = 0;
        /** @var BarcodeBar $bar */
        foreach ($barcode->getBars() as $bar) {
            $barWidth = round(($bar->getWidth() * $widthFactor), 3);
            $barHeight = round(($bar->getHeight() * $height / $barcode->getHeight()), 3);

            if ($bar->isBar() && $barWidth > 0) {
                $positionVertical = round(($bar->getPositionVertical() * $height / $barcode->getHeight()), 3);

                // draw a vertical bar
                $html .= '<div style="background-color:rgb(' . implode(',', $this->foregroundColor) . ');width:' . $barWidth . 'px;height:' . $barHeight . 'px;position:absolute;left:' . $positionHorizontal . 'px;top:' . $positionVertical . (($positionVertical > 0) ? 'px' : '') . '">&nbsp;</div>' . PHP_EOL;
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
