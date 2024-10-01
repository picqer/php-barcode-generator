<?php

use PHPUnit\Framework\TestCase;

class HtmlRendererTest extends TestCase
{
    public function test_html_barcode_generator_can_generate_code_128_barcode()
    {
        $barcode = (new Picqer\Barcode\Types\TypeCode128())->getBarcode('081231723897');

        $renderer = new Picqer\Barcode\Renderers\HtmlRenderer();
        $generated = $renderer->render($barcode, $barcode->getWidth() * 2);

        $this->assertStringEqualsFile('tests/verified-files/081231723897-code128.html', $generated);
    }

    public function test_html_barcode_generator_can_generate_imb_barcode_to_test_heights()
    {
        $barcode = (new Picqer\Barcode\Types\TypeIntelligentMailBarcode())->getBarcode('12345678903');

        $renderer = new Picqer\Barcode\Renderers\HtmlRenderer();
        $generated = $renderer->render($barcode, $barcode->getWidth() * 2);

        $this->assertStringEqualsFile('tests/verified-files/12345678903-imb.html', $generated);
    }

    public function test_html_barcode_generator_with_background()
    {
        $barcode = (new Picqer\Barcode\Types\TypeCode128())->getBarcode('081231723897');

        $renderer = new Picqer\Barcode\Renderers\HtmlRenderer();
        $renderer->setBackgroundColor([255, 0, 0]);
        $generated = $renderer->render($barcode, $barcode->getWidth() * 2);

        $this->assertStringEqualsFile('tests/verified-files/081231723897-code128-red-background.html', $generated);
    }
}
