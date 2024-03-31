<?php

use PHPUnit\Framework\TestCase;

class BarcodeSvgv3Test extends TestCase
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
}
