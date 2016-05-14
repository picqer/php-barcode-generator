<?php

class BarcodeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function png_barcode_generator_can_generate_code_128_barcode()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        $generated = $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);

        $this->assertEquals('PNG', substr($generated, 1, 3));
    }

    /**
     * @test
     */
    public function svg_barcode_generator_can_generate_ean_13_barcode()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $generated = $generator->getBarcode('081231723897', $generator::TYPE_EAN_13);

        $this->assertStringEqualsFile('tests/verified-files/081231723897-ean13.svg', $generated);
    }

    /**
     * @test
     */
    public function html_barcode_generator_can_generate_code_128_barcode()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
        $generated = $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);

        $this->assertStringEqualsFile('tests/verified-files/081231723897-code128.html', $generated);
    }

    /**
     * @test
     */
    public function jpg_barcode_generator_can_generate_code_128_barcode()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorJPG();
        $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);
    }

    /**
     * @test
     * @expectedException \Picqer\Barcode\Exceptions\InvalidCharacterException
     */
    public function ean13_generator_throws_exception_if_invalid_chars_are_used()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $generator->getBarcode('A123', $generator::TYPE_EAN_13);
    }
}