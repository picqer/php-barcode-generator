<?php

use PHPUnit\Framework\TestCase;

class BarcodeTest extends TestCase
{
    public function test_png_barcode_generator_can_generate_code_128_barcode()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorPNG();
        $generated = $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);

        $this->assertEquals('PNG', substr($generated, 1, 3));
    }

    public function test_svg_barcode_generator_can_generate_ean_13_barcode()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $generated = $generator->getBarcode('081231723897', $generator::TYPE_EAN_13);

        $this->assertStringEqualsFile('tests/verified-files/081231723897-ean13.svg', $generated);
    }

    public function test_html_barcode_generator_can_generate_code_128_barcode()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
        $generated = $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);

        $this->assertStringEqualsFile('tests/verified-files/081231723897-code128.html', $generated);
    }

    public function test_jpg_barcode_generator_can_generate_code_128_barcode()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorJPG();
        $result = $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);

        $this->assertGreaterThan(1000, strlen($result));
    }

    public function test_ean13_generator_throws_exception_if_invalid_chars_are_used()
    {
        $this->expectException(Picqer\Barcode\Exceptions\InvalidCharacterException::class);

        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $generator->getBarcode('A123', $generator::TYPE_EAN_13);
    }

    public function test_ean13_generator_accepting_13_chars()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $generated = $generator->getBarcode('0049000004632', $generator::TYPE_EAN_13);

        $this->assertStringEqualsFile('tests/verified-files/0049000004632-ean13.svg', $generated);
    }

    public function test_ean13_generator_accepting_12_chars_and_generates_13th_check_digit()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $generated = $generator->getBarcode('004900000463', $generator::TYPE_EAN_13);

        $this->assertStringEqualsFile('tests/verified-files/0049000004632-ean13.svg', $generated);
    }

    public function test_ean13_generator_accepting_11_chars_and_generates_13th_check_digit_and_adds_leading_zero()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $generated = $generator->getBarcode('04900000463', $generator::TYPE_EAN_13);

        $this->assertStringEqualsFile('tests/verified-files/0049000004632-ean13.svg', $generated);
    }

    public function test_ean13_generator_throws_exception_when_wrong_check_digit_is_given()
    {
        $this->expectException(Picqer\Barcode\Exceptions\InvalidCheckDigitException::class);

        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $generator->getBarcode('0049000004633', $generator::TYPE_EAN_13);
    }

    public function test_generator_throws_unknown_type_exceptions()
    {
        $this->expectException(Picqer\Barcode\Exceptions\UnknownTypeException::class);

        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();
        $generator->getBarcode('0049000004633', 'vladimir');
    }
}