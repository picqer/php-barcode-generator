# PHP Barcode Generator [![Build Status](https://travis-ci.org/picqer/php-barcode-generator.svg?branch=master)](https://travis-ci.org/picqer/php-barcode-generator) [![Total Downloads](https://poser.pugx.org/picqer/php-barcode-generator/downloads)](https://packagist.org/packages/picqer/php-barcode-generator)
This is an easy to use, non-bloated, framework independent, barcode generator in PHP.

It creates SVG, PNG, JPG and HTML images, from the most used 1D barcode standards.

*The codebase is largely from the [TCPDF barcode generator](https://github.com/tecnickcom/TCPDF) by Nicola Asuni. This code is therefor licensed under LGPLv3. It is still a bit of a mess, bit I will clean it in the future. I do not expect the interface of this class will change during the clean ups.*

## Installation
Install through [composer](https://getcomposer.org/doc/00-intro.md):

```
composer require picqer/php-barcode-generator
```

If you want to generate PNG or JPG images, you need the GD library or Imagick installed on your system as well.

Using IMB Barcodes require `bcmath` extension to be installed as well.

## Usage
Create a new barcode generator.  This will declare the code, the type of barcode and what format the code will be rendered.

```php
$generator = new BarcodeGenerator('012345678', BarcodeType::TYPE_CODE_128, BarcodeRender::RENDER_JPG);
$generated = $generator->generate();
```

The `$generator->generate()` method accepts the following:
- $widthFactor (default: 2) Width is based on the length of the data, with this factor you can make the barcode bars wider than default
- $totalHeight (default: 30) The total height of the barcode
- $color (default: #000000) Hex code of the foreground color

## Image types
```php
use Picqer\Barcode\Constants\BarcodeRender;

BarcodeRender::RENDER_JPG
BarcodeRender::RENDER_PNG
BarcodeRender::RENDER_HTML
BarcodeRender::RENDER_SVG
```

## Accepted types
```php
use Picqer\Barcode\Constants\BarcodeType;

BarcodeType::TYPE_CODE_39
BarcodeType::TYPE_CODE_39_CHECKSUM
BarcodeType::TYPE_CODE_39E
BarcodeType::TYPE_CODE_39E_CHECKSUM
BarcodeType::TYPE_CODE_93
BarcodeType::TYPE_STANDARD_2_5
BarcodeType::TYPE_STANDARD_2_5_CHECKSUM
BarcodeType::TYPE_INTERLEAVED_2_5
BarcodeType::TYPE_INTERLEAVED_2_5_CHECKSUM
BarcodeType::TYPE_CODE_128
BarcodeType::TYPE_CODE_128_A
BarcodeType::TYPE_CODE_128_B
BarcodeType::TYPE_CODE_128_C
BarcodeType::TYPE_EAN_2
BarcodeType::TYPE_EAN_5
BarcodeType::TYPE_EAN_8
BarcodeType::TYPE_EAN_13
BarcodeType::TYPE_UPC_A
BarcodeType::TYPE_UPC_E
BarcodeType::TYPE_MSI
BarcodeType::TYPE_MSI_CHECKSUM
BarcodeType::TYPE_POSTNET
BarcodeType::TYPE_PLANET
BarcodeType::TYPE_RMS4CC
BarcodeType::TYPE_KIX
BarcodeType::TYPE_IMB
BarcodeType::TYPE_CODABAR
BarcodeType::TYPE_CODE_11
BarcodeType::TYPE_PHARMA_CODE
BarcodeType::TYPE_PHARMA_CODE_TWO_TRACKS
```

## Examples
Embedded PNG image in HTML:

```php
$generator = new BarcodeGenerator('012345678', BarcodeType::TYPE_CODE_128, BarcodeRender::RENDER_JPG);
echo '<img src="data:image/png;base64,' . base64_encode($generator->generate()) . '">';
```
