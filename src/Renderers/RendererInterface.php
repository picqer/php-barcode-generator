<?php

namespace Picqer\Barcode\Renderers;

use Picqer\Barcode\Barcode;

interface RendererInterface
{
    public function render(Barcode $barcode, float $width = 200, float $height = 30): string;

    public function setForegroundColor(array $color): self;

    public function setBackgroundColor(?array $color): self;
}
