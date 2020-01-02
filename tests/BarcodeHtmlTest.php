<?php

use PHPUnit\Framework\TestCase;

class BarcodeHtmlTest extends TestCase
{
    public function test_html_barcode_generator_can_generate_code_128_barcode()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
        $generated = $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);

        $this->assertStringEqualsFile('tests/verified-files/081231723897-code128.html', $generated);
    }
}
