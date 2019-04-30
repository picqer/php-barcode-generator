<?php

namespace Test\Unit;

use Picqer\Barcode\BarcodeGenerator;
use Picqer\Barcode\Constants\BarcodeRender;
use Picqer\Barcode\Constants\BarcodeType;
use Test\BaseTestCase;

class IMBTest extends BaseTestCase
{
    const VALID_CODE = '081231723897';

    public function test_IMBGeneratesJPGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_IMB, BarcodeRender::RENDER_JPG);
        $generated = $generator->generate();

        $this->assertContains('JPEG', $generated);
    }

    public function test_IMBGeneratesPNGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_IMB, BarcodeRender::RENDER_PNG);
        $generated = $generator->generate();

        $this->assertEquals('PNG', substr($generated, 1, 3));
    }

    public function test_IMBGeneratesHTMLStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_IMB, BarcodeRender::RENDER_HTML);
        $generated = $generator->generate();

        $this->assertContains('<div', $generated);
    }

    public function test_IMBGeneratesSVGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_IMB, BarcodeRender::RENDER_SVG);
        $generated = $generator->generate();

        $this->assertContains('<svg', $generated);
    }
}
