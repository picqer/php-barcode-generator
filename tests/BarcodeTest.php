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

    /**
     * @test
     */
    public function ean13_generator_accepting_13_chars()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $generated = $generator->getBarcode('0049000004632', $generator::TYPE_EAN_13);

        $this->assertStringEqualsFile('tests/verified-files/0049000004632-ean13.svg', $generated);
    }

    /**
     * @test
     */
    public function ean13_generator_accepting_12_chars_and_generates_13th_check_digit()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $generated = $generator->getBarcode('004900000463', $generator::TYPE_EAN_13);

        $this->assertStringEqualsFile('tests/verified-files/0049000004632-ean13.svg', $generated);
    }

    /**
     * @test
     */
    public function ean13_generator_accepting_11_chars_and_generates_13th_check_digit_and_adds_leading_zero()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $generated = $generator->getBarcode('04900000463', $generator::TYPE_EAN_13);

        $this->assertStringEqualsFile('tests/verified-files/0049000004632-ean13.svg', $generated);
    }

    /**
     * @test
     * @expectedException \Picqer\Barcode\Exceptions\InvalidCheckDigitException
     */
    public function ean13_generator_throws_exception_when_wrong_check_digit_is_given()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $generator->getBarcode('0049000004633', $generator::TYPE_EAN_13);
    }

    /**
     * @test
     * @expectedException \Picqer\Barcode\Exceptions\UnknownTypeException
     */
    public function generator_throws_unknown_type_exceptions()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $generator->getBarcode('0049000004633', 'vladimir');
    }
}