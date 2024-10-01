# PHP Barcode Generator 
<a href="https://github.com/picqer/php-barcode-generator/actions"><img src="https://github.com/picqer/php-barcode-generator/workflows/phpunit/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/picqer/php-barcode-generator"><img src="https://img.shields.io/packagist/dt/picqer/php-barcode-generator" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/picqer/php-barcode-generator"><img src="https://img.shields.io/packagist/v/picqer/php-barcode-generator" alt="Latest Stable Version"></a>

This is an easy to use, non-bloated, framework independent, barcode generator in PHP. It uses zero(!) composer dependencies and is only a handful of files. Probably the reason that this is the most downloaded barcode generator for PHP on Packagist. ;)

It creates SVG, PNG, JPG and HTML images, from the most used 1D barcode standards.

## No support for...
- No support for any **2D** barcodes, like QR codes.
- We only generate the 'bars' part of a barcode, without text below the barcode. If you want text of the code below the barcode, you could add it later to the output of this package. 

## Installation
Install through [composer](https://getcomposer.org/doc/00-intro.md):

```
composer require picqer/php-barcode-generator
```

If you want to generate PNG or JPG images, you need the GD library or Imagick installed on your system as well. For SVG or HTML renders, there are no dependencies.

## Usage
You want a barcode for a specific "type" (for example Code 128 or UPC) in a specific image format (for example PNG or SVG).

- First, encode the string you want the barcode of into a `Barcode` object with one of the barcode types.
- Then, use one of the renderers to render the image of the bars in the `Barcode` object.

> The "type" is a standard that defines which characters you can encode and which bars represent which character. The most used types are [code 128](https://en.wikipedia.org/wiki/Code_128) and [EAN/UPC](https://en.wikipedia.org/wiki/International_Article_Number). Not all characters can be encoded into each barcode type, and not all barcode scanners can read all types.

```php
<?php
require 'vendor/autoload.php';

// Make Barcode object of Code128 encoding.
$barcode = (new Picqer\Barcode\Types\TypeCode128())->getBarcode('081231723897');

// Output the barcode as HTML in the browser with a HTML Renderer
$renderer = new Picqer\Barcode\Renderers\HtmlRenderer();
echo $renderer->render($barcode);
```

Will result in this beauty:<br>
![Barcode 081231723897 as Code 128](tests/verified-files/081231723897-ean13.svg)

Each renderer has their own options. For example, you can set the height, width and color of a PNG:
```php
<?php
require 'vendor/autoload.php';

$colorRed = [255, 0, 0];

$barcode = (new Picqer\Barcode\Types\TypeCode128())->getBarcode('081231723897');
$renderer = new Picqer\Barcode\Renderers\PngRenderer();
$renderer->setForegroundColor($colorRed);

// Save PNG to the filesystem, with widthFactor 3 (width of the barcode x 3) and height of 50 pixels
file_put_contents('barcode.png', $renderer->render($barcode, $barcode->getWidth() * 3, 50));
```

## Image renderers
Available image renderers: SVG, PNG, JPG and HTML.

They all conform to the RendererInterface and have the same `render()` method. Some renderers have extra options as well, via set*() methods.

### Widths
The render() method needs the Barcode object, the width and height. **For JPG/PNG images**, you only get a valid barcode if you give a width that is a factor of the width of the Barcode object. That is why the examples show `$barcode->getWidth() * 2` to make the image 2 times wider in pixels then the width of the barcode data. You *can* give an arbitrary number as width and the image will be scaled as best as possible, but without anti-aliasing, it will not be perfectly valid.

HTML and SVG renderers can handle any width and height, even floats.

Here are all the options for each renderer:

### SVG
A vector based SVG image. Gives the best quality to print.
```php
$renderer = new Picqer\Barcode\Renderers\SvgRenderer();
$renderer->setForegroundColor([255, 0, 0]); // Give a color red for the bars, default is black. Give it as 3 times 0-255 values for red, green and blue. 
$renderer->setBackgroundColor([0, 0, 255]); // Give a color blue for the background, default is transparent. Give it as 3 times 0-255 values for red, green and blue. 
$renderer->setSvgType($renderer::TYPE_SVG_INLINE); // Changes the output to be used inline inside HTML documents, instead of a standalone SVG image (default)
$renderer->setSvgType($renderer::TYPE_SVG_STANDALONE); // If you want to force the default, create a stand alone SVG image

$renderer->render($barcode, 450.20, 75); // Width and height support floats
````

### PNG + JPG
All options for PNG and JPG are the same.
```php
$renderer = new Picqer\Barcode\Renderers\PngRenderer();
$renderer->setForegroundColor([255, 0, 0]); // Give a color for the bars, default is black. Give it as 3 times 0-255 values for red, green and blue. 
$renderer->setBackgroundColor([0, 255, 255]); // Give a color for the background, default is transparent (in PNG) or white (in JPG). Give it as 3 times 0-255 values for red, green and blue. 
$renderer->useGd(); // If you have Imagick and GD installed, but want to use GD
$renderer->useImagick(); // If you have Imagick and GD installed, but want to use Imagick

$renderer->render($barcode, 5, 40); // Width factor (how many pixel wide every bar is), and the height in pixels
````

### HTML
Gives HTML to use inline in a full HTML document.
```php
$renderer = new Picqer\Barcode\Renderers\HtmlRenderer();
$renderer->setForegroundColor([255, 0, 0]); // Give a color red for the bars, default is black. Give it as 3 times 0-255 values for red, green and blue. 
$renderer->setBackgroundColor([0, 0, 255]); // Give a color blue for the background, default is transparent. Give it as 3 times 0-255 values for red, green and blue. 

$renderer->render($barcode, 450.20, 75); // Width and height support floats
````

### Dynamic HTML
Give HTML here the barcode is using the full width and height, to put inside a container/div that has a fixed size.
```php
$renderer = new Picqer\Barcode\Renderers\DynamicHtmlRenderer();
$renderer->setForegroundColor([255, 0, 0]); // Give a color red for the bars, default is black. Give it as 3 times 0-255 values for red, green and blue. 
$renderer->setBackgroundColor([0, 0, 255]); // Give a color blue for the background, default is transparent. Give it as 3 times 0-255 values for red, green and blue. 

$renderer->render($barcode);
````

You can put the rendered HTML inside a div like this:
```html
<div style="width: 400px; height: 75px"><?php echo $renderedBarcode; ?></div>
```

## Accepted barcode types
These barcode types are supported. All types support different character sets and some have mandatory lengths. Please see wikipedia for supported chars and lengths per type.

You can find all supported types in the [src/Types](src/Types) folder.

Most used types are TYPE_CODE_128 and TYPE_CODE_39. Because of the best scanner support, variable length and most chars supported.

- TYPE_CODE_32 (italian pharmaceutical code 'MINSAN')
- TYPE_CODE_39
- TYPE_CODE_39_CHECKSUM
- TYPE_CODE_39E
- TYPE_CODE_39E_CHECKSUM
- TYPE_CODE_93
- TYPE_STANDARD_2_5
- TYPE_STANDARD_2_5_CHECKSUM
- TYPE_INTERLEAVED_2_5
- TYPE_INTERLEAVED_2_5_CHECKSUM
- TYPE_CODE_128
- TYPE_CODE_128_A
- TYPE_CODE_128_B
- TYPE_CODE_128_C
- TYPE_EAN_2
- TYPE_EAN_5
- TYPE_EAN_8
- TYPE_EAN_13
- TYPE_ITF14 (Also known as GTIN-14)
- TYPE_UPC_A
- TYPE_UPC_E
- TYPE_MSI
- TYPE_MSI_CHECKSUM
- TYPE_POSTNET
- TYPE_PLANET
- TYPE_RMS4CC
- TYPE_KIX
- TYPE_IMB
- TYPE_CODABAR
- TYPE_CODE_11
- TYPE_PHARMA_CODE
- TYPE_PHARMA_CODE_TWO_TRACKS

[See example images for all supported barcode types](examples.md)

## A note about PNG and JPG images
If you want to use PNG or JPG images, you need to install [Imagick](https://www.php.net/manual/en/intro.imagick.php) or the [GD library](https://www.php.net/manual/en/intro.image.php). This package will use Imagick if that is installed, or fall back to GD. If you have both installed, but you want a specific method, you can use `$renderer->useGd()` or `$renderer->useImagick()` to force your preference.

## Examples

### Embedded PNG image in HTML
```php
$barcode = (new Picqer\Barcode\Types\TypeCode128())->getBarcode('081231723897');
$renderer = new Picqer\Barcode\Renderers\PngRenderer();
echo '<img src="data:image/png;base64,' . base64_encode($renderer->render($barcode, $barcode->getWidth() * 2)) . '">';
```

### Save JPG barcode to disk
```php
$barcode = (new Picqer\Barcode\Types\TypeCodabar())->getBarcode('081231723897');
$renderer = new Picqer\Barcode\Renderers\JpgRenderer();

file_put_contents('barcode.jpg', $renderer->render($barcode, $barcode->getWidth() * 2));
```

### Oneliner SVG output to disk
```php
file_put_contents('barcode.svg', (new Picqer\Barcode\Renderers\SvgRenderer())->render((new Picqer\Barcode\Types\TypeKix())->getBarcode('6825ME601')));
```

## Upgrading to v3
There is no need to change anything when upgrading from v2 to v3. Above you find the new preferred way of using this library since v3. But the old style still works.

To give the renderers the same interface, setting colors is now always with an array of RGB colors. If you use the old BarcodeGenerator* classes and use colors with names ('red') or hex codes (#3399ef), these will be converted using the ColorHelper. All hexcodes are supported, but for names of colors only the basic colors are supported.

If you want to convert to the new style, here is an example:
```php
// Old style
$generator = new Picqer\Barcode\BarcodeGeneratorSVG();
echo $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);

// New style
$barcode = (new Picqer\Barcode\Types\TypeCode128())->getBarcode('081231723897');
$renderer = new Picqer\Barcode\Renderers\SvgRenderer();
echo $renderer->render($barcode);
```

The width in the renderer is now the width of the end result, instead of the widthFactor. If you want to keep dynamic widths, you can get the width of the encoded Barcode and multiply it by the widthFactor to get the same result as before. See here an example for a widthFactor of 2:
```php
// Old style
$generator = new Picqer\Barcode\BarcodeGeneratorSVG();
echo $generator->getBarcode('081231723897', $generator::TYPE_CODE_128, 2. 30);

// New style
$barcode = (new Picqer\Barcode\Types\TypeCode128())->getBarcode('081231723897');
$renderer = new Picqer\Barcode\Renderers\SvgRenderer();
echo $renderer->render($barcode, $barcode->getWidth() * 2, 30);
```

---

## Previous style generators
In version 3 the barcode type encoders and image renderers are completely separate. This makes building your own renderer way easier. The old way was using "generators". Below are the old examples of these generators, which still works in v3 as well.

### Usage
Initiate the barcode generator for the output you want, then call the ->getBarcode() routine as many times as you want.

```php
<?php
require 'vendor/autoload.php';

// This will output the barcode as HTML output to display in the browser
$generator = new Picqer\Barcode\BarcodeGeneratorHTML();
echo $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);
```

Will result in this beauty:<br>
![Barcode 081231723897 as Code 128](tests/verified-files/081231723897-ean13.svg)

The `getBarcode()` method accepts the following parameters:
- `$barcode` String needed to encode in the barcode
- `$type` Type of barcode, use the constants defined in the class
- `$widthFactor` Width is based on the length of the data, with this factor you can make the barcode bars wider than default
- `$height` The total height of the barcode in pixels
- `$foregroundColor` Hex code as string, or array of RGB, of the colors of the bars (the foreground color)

Example of usage of all parameters:

```php
<?php

require 'vendor/autoload.php';

$redColor = [255, 0, 0];

$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
file_put_contents('barcode.png', $generator->getBarcode('081231723897', $generator::TYPE_CODE_128, 3, 50, $redColor));
```

### Image types
```php
$generatorSVG = new Picqer\Barcode\BarcodeGeneratorSVG(); // Vector based SVG
$generatorPNG = new Picqer\Barcode\BarcodeGeneratorPNG(); // Pixel based PNG
$generatorJPG = new Picqer\Barcode\BarcodeGeneratorJPG(); // Pixel based JPG
$generatorHTML = new Picqer\Barcode\BarcodeGeneratorHTML(); // Pixel based HTML
$generatorHTML = new Picqer\Barcode\BarcodeGeneratorDynamicHTML(); // Vector based HTML
```

#### Embedded PNG image in HTML
```php
$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
echo '<img src="data:image/png;base64,' . base64_encode($generator->getBarcode('081231723897', $generator::TYPE_CODE_128)) . '">';
```

#### Save JPG barcode to disk
```php
$generator = new Picqer\Barcode\BarcodeGeneratorJPG();
file_put_contents('barcode.jpg', $generator->getBarcode('081231723897', $generator::TYPE_CODABAR));
```

#### Oneliner SVG output to disk
```php
file_put_contents('barcode.svg', (new Picqer\Barcode\BarcodeGeneratorSVG())->getBarcode('6825ME601', Picqer\Barcode\BarcodeGeneratorSVG::TYPE_KIX));
```

---
*The codebase is based on the [TCPDF barcode generator](https://github.com/tecnickcom/TCPDF) by Nicola Asuni. This code is therefor licensed under LGPLv3.*
