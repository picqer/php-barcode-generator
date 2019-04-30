<?php

namespace Picqer\Barcode\Renders;

interface BarcodeRenderInterface
{
    public function render(array $barcodeData, int $widthFactor = 2, int $totalHeight = 30, string $color = '#000000'): string;
}