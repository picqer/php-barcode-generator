<?php

namespace Picqer\Barcode;

class BarcodeGeneratorJPG extends BarcodeGeneratorImage
{
	const EXTENSION = 'JPG';
	const DEFAULT_BACKGROUND_COLOR = 'white';

    protected function generateGdImage($image)
    {
        imagejpeg($image);
		parent::generateGdImage($image);
    }
}
