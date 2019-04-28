<?php

namespace Picqer\Barcode\Renders;

use Picqer\Barcode\Constants\BarcodeRender;

class BarcodeRenderFactory
{
    public function generateBarcodeRender(string $barcodeRenderType): BarcodeRenderInterface
    {
        switch (strtoupper($barcodeRenderType)) {
            case BarcodeRender::RENDER_HTML:
                return new Html();
                break;
            case BarcodeRender::RENDER_SVG:
                return new Svg();
                break;
            case BarcodeRender::RENDER_JPG:
                return new Jpg();
                break;
            case BarcodeRender::RENDER_PNG:
                return new Png();
                break;
            default:
                throw new UnknownTypeException();
                break;
        }
    }
}
