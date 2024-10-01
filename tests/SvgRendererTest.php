<?php

use PHPUnit\Framework\TestCase;

class SvgRendererTest extends TestCase
{
    public function test_svg_barcode_generator_can_generate_ean_13_barcode()
    {
        $barcode = (new Picqer\Barcode\Types\TypeEan13())->getBarcode('081231723897');

        $renderer = new Picqer\Barcode\Renderers\SvgRenderer();
        $generated = $renderer->render($barcode, 190);

        $this->assertStringEqualsFile('tests/verified-files/081231723897-ean13.svg', $generated);
    }

    public function test_svg_barcode_generator_can_generate_ean_13_barcode_with_fractional_width()
    {
        $barcode = (new Picqer\Barcode\Types\TypeEan13())->getBarcode('081231723897');

        $renderer = new Picqer\Barcode\Renderers\SvgRenderer();
        $generated = $renderer->render($barcode, 23.75, 25.75);

        $this->assertStringEqualsFile('tests/verified-files/081231723897-ean13-fractional-width.svg', $generated);
    }

    public function test_svg_barcode_generator_as_standalone()
    {
        $barcode = (new Picqer\Barcode\Types\TypeEan13())->getBarcode('081231723897');

        $renderer = new Picqer\Barcode\Renderers\SvgRenderer();
        $renderer->setSvgType(\Picqer\Barcode\Renderers\SvgRenderer::TYPE_SVG_STANDALONE);
        $generated = $renderer->render($barcode);

        $this->assertStringStartsWith('<?xml version="1.0"', $generated);
        $this->assertStringContainsString('<!DOCTYPE svg PUBLIC', $generated);
    }

    public function test_svg_barcode_generator_as_inline()
    {
        $barcode = (new Picqer\Barcode\Types\TypeEan13())->getBarcode('081231723897');

        $renderer = new Picqer\Barcode\Renderers\SvgRenderer();
        $renderer->setSvgType(\Picqer\Barcode\Renderers\SvgRenderer::TYPE_SVG_INLINE);
        $generated = $renderer->render($barcode);

        $this->assertStringStartsWith('<svg width=', $generated);
        $this->assertStringNotContainsString('<?xml version="1.0"', $generated);
        $this->assertStringNotContainsString('<!DOCTYPE svg PUBLIC', $generated);
    }

    public function test_svg_renderer_throws_exception_wrong_type()
    {
        $this->expectException(\Picqer\Barcode\Exceptions\InvalidOptionException::class);

        $renderer = new Picqer\Barcode\Renderers\SvgRenderer();
        $renderer->setSvgType('other');
    }

    public function test_svg_barcode_generator_can_use_background_color()
    {
        $barcode = (new Picqer\Barcode\Types\TypeEan13())->getBarcode('081231723897');

        $renderer = new Picqer\Barcode\Renderers\SvgRenderer();
        $renderer->setBackgroundColor([255, 0, 0]);
        $generated = $renderer->render($barcode, 190);

        $this->assertStringEqualsFile('tests/verified-files/081231723897-ean13-red-background.svg', $generated);
    }
}
