<?php

use PHPUnit\Framework\TestCase;

class PngRendererTest extends TestCase
{
    public function test_png_barcode_generator_can_generate_code_128_barcode()
    {
        $barcode = (new Picqer\Barcode\Types\TypeCode128())->getBarcode('081231723897');

        $renderer = new Picqer\Barcode\Renderers\PngRenderer();
        $renderer->useGd();
        $generated = $renderer->render($barcode);

        $this->assertEquals('PNG', substr($generated, 1, 3));
    }


    public function test_png_barcode_generator_can_generate_code_39_barcode()
    {
        $barcode = (new Picqer\Barcode\Types\TypeCode39())->getBarcode('081231723897');

        $renderer = new Picqer\Barcode\Renderers\PngRenderer();
        $renderer->useGd();
        $generated = $renderer->render($barcode, $barcode->getWidth());

        $imageInfo = getimagesizefromstring($generated);

        $this->assertGreaterThan(100, strlen($generated));
        $this->assertEquals(224, $imageInfo[0]); // Image width
        $this->assertEquals(30, $imageInfo[1]); // Image height
        $this->assertEquals('image/png', $imageInfo['mime']);
    }

    public function test_png_barcode_generator_can_use_different_height()
    {
        $barcode = (new Picqer\Barcode\Types\TypeCode128())->getBarcode('081231723897');

        $renderer = new Picqer\Barcode\Renderers\PngRenderer();
        $renderer->useGd();
        $generated = $renderer->render($barcode, $barcode->getWidth() * 2, 45);

        $imageInfo = getimagesizefromstring($generated);

        $this->assertGreaterThan(100, strlen($generated));
        $this->assertEquals(202, $imageInfo[0]); // Image width
        $this->assertEquals(45, $imageInfo[1]); // Image height
        $this->assertEquals('image/png', $imageInfo['mime']);
    }

    public function test_png_barcode_generator_can_use_different_width_factor()
    {
        $barcode = (new Picqer\Barcode\Types\TypeCode128())->getBarcode('081231723897');

        $renderer = new Picqer\Barcode\Renderers\PngRenderer();
        $renderer->useGd();
        $generated = $renderer->render($barcode, $barcode->getWidth() * 5);

        $imageInfo = getimagesizefromstring($generated);

        $this->assertGreaterThan(100, strlen($generated));
        $this->assertEquals(505, $imageInfo[0]); // Image width
        $this->assertEquals('image/png', $imageInfo['mime']);
    }


    // Copied as Imagick

    public function test_png_barcode_generator_can_generate_code_128_barcode_imagick()
    {
        if (! extension_loaded('imagick')) {
            $this->markTestSkipped();
        }

        $barcode = (new Picqer\Barcode\Types\TypeCode128())->getBarcode('081231723897');

        $renderer = new Picqer\Barcode\Renderers\PngRenderer();
        $renderer->useImagick();
        $generated = $renderer->render($barcode, $barcode->getWidth() * 2);

        $this->assertEquals('PNG', substr($generated, 1, 3));
    }

    public function test_png_barcode_generator_can_generate_code_39_barcode_imagick()
    {
        if (! extension_loaded('imagick')) {
            $this->markTestSkipped();
        }

        $barcode = (new Picqer\Barcode\Types\TypeCode39())->getBarcode('081231723897');

        $renderer = new Picqer\Barcode\Renderers\PngRenderer();
        $renderer->useImagick();
        $generated = $renderer->render($barcode, $barcode->getWidth());

        $imageInfo = getimagesizefromstring($generated);

        $this->assertGreaterThan(100, strlen($generated));
        $this->assertEquals(224, $imageInfo[0]); // Image width
        $this->assertEquals(30, $imageInfo[1]); // Image height
        $this->assertEquals('image/png', $imageInfo['mime']);
    }

    public function test_png_barcode_generator_can_use_different_height_imagick()
    {
        if (! extension_loaded('imagick')) {
            $this->markTestSkipped();
        }

        $barcode = (new Picqer\Barcode\Types\TypeCode128())->getBarcode('081231723897');

        $renderer = new Picqer\Barcode\Renderers\PngRenderer();
        $renderer->useGd();
        $generated = $renderer->render($barcode, $barcode->getWidth() * 2, 45);

        $imageInfo = getimagesizefromstring($generated);

        $this->assertGreaterThan(100, strlen($generated));
        $this->assertEquals(202, $imageInfo[0]); // Image width
        $this->assertEquals(45, $imageInfo[1]); // Image height
        $this->assertEquals('image/png', $imageInfo['mime']);
    }

    public function test_png_barcode_generator_can_use_different_width_factor_imagick()
    {
        if (! extension_loaded('imagick')) {
            $this->markTestSkipped();
        }

        $barcode = (new Picqer\Barcode\Types\TypeCode128())->getBarcode('081231723897');

        $renderer = new Picqer\Barcode\Renderers\PngRenderer();
        $renderer->useGd();
        $generated = $renderer->render($barcode, $barcode->getWidth() * 5);

        $imageInfo = getimagesizefromstring($generated);

        $this->assertGreaterThan(100, strlen($generated));
        $this->assertEquals(505, $imageInfo[0]); // Image width
        $this->assertEquals('image/png', $imageInfo['mime']);
    }
}
