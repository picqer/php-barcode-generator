<?php

use PHPUnit\Framework\TestCase;

class DynamicHtmlRendererTest extends TestCase
{
    public function test_dynamic_html_barcode_generator_can_generate_code_128_barcode()
    {
        $barcode = (new Picqer\Barcode\Types\TypeCode128())->getBarcode('081231723897');

        $renderer = new Picqer\Barcode\Renderers\DynamicHtmlRenderer();
        $generated = $renderer->render($barcode);

        $this->assertStringEqualsFile('tests/verified-files/081231723897-dynamic-code128.html', $generated);
    }

    public function test_dynamic_html_barcode_generator_can_generate_imb_barcode_to_test_heights()
    {
        $barcode = (new Picqer\Barcode\Types\TypeIntelligentMailBarcode())->getBarcode('12345678903');

        $renderer = new Picqer\Barcode\Renderers\DynamicHtmlRenderer();
        $generated = $renderer->render($barcode);

        $this->assertStringEqualsFile('tests/verified-files/12345678903-dynamic-imb.html', $generated);
    }
}
