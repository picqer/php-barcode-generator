<?php

namespace Test\Unit;

use Picqer\Barcode\BarcodeGenerator;
use Picqer\Barcode\Constants\BarcodeRender;
use Picqer\Barcode\Constants\BarcodeType;
use Test\BaseTestCase;

class MSITest extends BaseTestCase
{
    const VALID_CODE = '081231723897';

    public function test_MSIGeneratesJPGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_MSI, BarcodeRender::RENDER_JPG);
        $generated = $generator->generate();

        $this->assertContains('JPEG', $generated);
    }

    public function test_MSIGeneratesPNGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_MSI, BarcodeRender::RENDER_PNG);
        $generated = $generator->generate();

        $this->assertEquals('PNG', substr($generated, 1, 3));
    }

    public function test_MSIGeneratesHTMLStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_MSI, BarcodeRender::RENDER_HTML);
        $generated = $generator->generate();

        $this->assertContains('<div', $generated);
    }

    public function test_MSIGeneratesSVGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_MSI, BarcodeRender::RENDER_SVG);
        $generated = $generator->generate();

        $this->assertContains('<svg', $generated);
    }

    public function test_MSIChecksumGeneratesJPGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_MSI_CHECKSUM, BarcodeRender::RENDER_JPG);
        $generated = $generator->generate();

        $this->assertContains('JPEG', $generated);
    }

    public function test_MSIChecksumGeneratesPNGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_MSI_CHECKSUM, BarcodeRender::RENDER_PNG);
        $generated = $generator->generate();

        $this->assertEquals('PNG', substr($generated, 1, 3));
    }

    public function test_MSIChecksumGeneratesHTMLStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_MSI_CHECKSUM, BarcodeRender::RENDER_HTML);
        $generated = $generator->generate();

        $this->assertContains('<div', $generated);
    }

    public function test_MSIChecksumGeneratesSVGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_MSI_CHECKSUM, BarcodeRender::RENDER_SVG);
        $generated = $generator->generate();

        $this->assertContains('<svg', $generated);
    }
}
