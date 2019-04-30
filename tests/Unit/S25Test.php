<?php

namespace Test\Unit;

use Picqer\Barcode\BarcodeGenerator;
use Picqer\Barcode\Constants\BarcodeRender;
use Picqer\Barcode\Constants\BarcodeType;
use Test\BaseTestCase;

class S25Test extends BaseTestCase
{
    const VALID_CODE = '081231723897';

    public function test_S25GeneratesJPGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_STANDARD_2_5, BarcodeRender::RENDER_JPG);
        $generated = $generator->generate();

        $this->assertContains('JPEG', $generated);
    }

    public function test_S25GeneratesPNGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_STANDARD_2_5, BarcodeRender::RENDER_PNG);
        $generated = $generator->generate();

        $this->assertEquals('PNG', substr($generated, 1, 3));
    }

    public function test_S25GeneratesHTMLStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_STANDARD_2_5, BarcodeRender::RENDER_HTML);
        $generated = $generator->generate();

        $this->assertContains('<div', $generated);
    }

    public function test_S25GeneratesSVGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_STANDARD_2_5, BarcodeRender::RENDER_SVG);
        $generated = $generator->generate();

        $this->assertContains('<svg', $generated);
    }

    public function test_S25ChecksumGeneratesJPGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_STANDARD_2_5_CHECKSUM, BarcodeRender::RENDER_JPG);
        $generated = $generator->generate();

        $this->assertContains('JPEG', $generated);
    }

    public function test_S25ChecksumGeneratesPNGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_STANDARD_2_5_CHECKSUM, BarcodeRender::RENDER_PNG);
        $generated = $generator->generate();

        $this->assertEquals('PNG', substr($generated, 1, 3));
    }

    public function test_S25ChecksumGeneratesHTMLStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_STANDARD_2_5_CHECKSUM, BarcodeRender::RENDER_HTML);
        $generated = $generator->generate();

        $this->assertContains('<div', $generated);
    }

    public function test_S25ChecksumGeneratesSVGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_STANDARD_2_5_CHECKSUM, BarcodeRender::RENDER_SVG);
        $generated = $generator->generate();

        $this->assertContains('<svg', $generated);
    }
}
