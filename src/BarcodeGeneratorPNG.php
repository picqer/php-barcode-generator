<?php

namespace Picqer\Barcode;

class BarcodeGeneratorPNG extends BarcodeGeneratorImage
{
	const EXTENSION = 'PNG';
	const DEFAULT_BACKGROUND_COLOR = 'none';

    protected function generateGdImage($image)
    {
        imagepng($image);
        parent::generateGdImage($image);
    }
}
