<?php

namespace Test\Unit;

use Picqer\Barcode\BarcodeGenerator;
use Picqer\Barcode\Constants\BarcodeRender;
use Picqer\Barcode\Constants\BarcodeType;
use Test\BaseTestCase;

class CodabarTest extends BaseTestCase
{
    const VALID_CODE = '081231723897';

    public function test_CodabarGeneratesJPGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_CODABAR, BarcodeRender::RENDER_JPG);
        $generated = $generator->generate();

        $this->assertContains('JPEG', $generated);
    }

    public function test_CodabarGeneratesPNGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_CODABAR, BarcodeRender::RENDER_PNG);
        $generated = $generator->generate();

        $this->assertEquals('PNG', substr($generated, 1, 3));
    }

    public function test_CodabarGeneratesHTMLStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_CODABAR, BarcodeRender::RENDER_HTML);
        $generated = $generator->generate();

        $this->assertContains('<div', $generated);
    }

    public function test_CodabarGeneratesSVGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_CODABAR, BarcodeRender::RENDER_SVG);
        $generated = $generator->generate();

        $this->assertContains('<svg', $generated);
    }
}
