<?php

namespace Picqer\Barcode\Renderers;

use Picqer\Barcode\Barcode;

interface RendererInterface
{
    public function render(Barcode $barcode, float $width = 200, float $height = 30): string;

    /** @param array{int, int, int} $color */
    public function setForegroundColor(array $color): self;

    /** @param array{int, int, int}|null $color */
    public function setBackgroundColor(?array $color): self;
}
