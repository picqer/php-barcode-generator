<?php

namespace Test\Unit;

use Picqer\Barcode\BarcodeGenerator;
use Picqer\Barcode\Constants\BarcodeRender;
use Picqer\Barcode\Constants\BarcodeType;
use Test\BaseTestCase;

class EanUpcTest extends BaseTestCase
{
    const VALID_CODE_EAN_8 = '96385074';
    const VALID_CODE_EAN_13 = '5901234123457';
    const VALID_CODE_UPC_A = '8123175';
    const VALID_CODE_UPC_E = '8123175';

    public function test_EanUpc8GeneratesJPGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE_EAN_8, BarcodeType::TYPE_EAN_8, BarcodeRender::RENDER_JPG);
        $generated = $generator->generate();

        $this->assertContains('JPEG', $generated);
    }

    public function test_EanUpc8GeneratesPNGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE_EAN_8, BarcodeType::TYPE_EAN_8, BarcodeRender::RENDER_PNG);
        $generated = $generator->generate();

        $this->assertEquals('PNG', substr($generated, 1, 3));
    }

    public function test_EanUpc8GeneratesHTMLStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE_EAN_8, BarcodeType::TYPE_EAN_8, BarcodeRender::RENDER_HTML);
        $generated = $generator->generate();

        $this->assertContains('<div', $generated);
    }

    public function test_EanUpc8GeneratesSVGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE_EAN_8, BarcodeType::TYPE_EAN_8, BarcodeRender::RENDER_SVG);
        $generated = $generator->generate();

        $this->assertContains('<svg', $generated);
    }

    public function test_EanUpc13GeneratesJPGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE_EAN_13, BarcodeType::TYPE_EAN_13, BarcodeRender::RENDER_JPG);
        $generated = $generator->generate();

        $this->assertContains('JPEG', $generated);
    }

    public function test_EanUpc13GeneratesPNGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE_EAN_13, BarcodeType::TYPE_EAN_13, BarcodeRender::RENDER_PNG);
        $generated = $generator->generate();

        $this->assertEquals('PNG', substr($generated, 1, 3));
    }

    public function test_EanUpc13GeneratesHTMLStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE_EAN_13, BarcodeType::TYPE_EAN_13, BarcodeRender::RENDER_HTML);
        $generated = $generator->generate();

        $this->assertContains('<div', $generated);
    }

    public function test_EanUpc13GeneratesSVGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE_EAN_13, BarcodeType::TYPE_EAN_13, BarcodeRender::RENDER_SVG);
        $generated = $generator->generate();

        $this->assertContains('<svg', $generated);
    }

    public function test_EanUpcAGeneratesJPGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE_UPC_A, BarcodeType::TYPE_UPC_A, BarcodeRender::RENDER_JPG);
        $generated = $generator->generate();

        $this->assertContains('JPEG', $generated);
    }

    public function test_EanUpcAGeneratesPNGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE_UPC_A, BarcodeType::TYPE_UPC_A, BarcodeRender::RENDER_PNG);
        $generated = $generator->generate();

        $this->assertEquals('PNG', substr($generated, 1, 3));
    }

    public function test_EanUpcAGeneratesHTMLStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE_UPC_A, BarcodeType::TYPE_UPC_A, BarcodeRender::RENDER_HTML);
        $generated = $generator->generate();

        $this->assertContains('<div', $generated);
    }

    public function test_EanUpcAGeneratesSVGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE_UPC_A, BarcodeType::TYPE_UPC_A, BarcodeRender::RENDER_SVG);
        $generated = $generator->generate();

        $this->assertContains('<svg', $generated);
    }

    public function test_EanUpcEGeneratesJPGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE_UPC_E, BarcodeType::TYPE_UPC_E, BarcodeRender::RENDER_JPG);
        $generated = $generator->generate();

        $this->assertContains('JPEG', $generated);
    }

    public function test_EanUpcEGeneratesPNGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE_UPC_E, BarcodeType::TYPE_UPC_E, BarcodeRender::RENDER_PNG);
        $generated = $generator->generate();

        $this->assertEquals('PNG', substr($generated, 1, 3));
    }

    public function test_EanUpcEGeneratesHTMLStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE_UPC_E, BarcodeType::TYPE_UPC_E, BarcodeRender::RENDER_HTML);
        $generated = $generator->generate();

        $this->assertContains('<div', $generated);
    }

    public function test_EanUpcEGeneratesSVGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE_UPC_E, BarcodeType::TYPE_UPC_E, BarcodeRender::RENDER_SVG);
        $generated = $generator->generate();

        $this->assertContains('<svg', $generated);
    }
}
