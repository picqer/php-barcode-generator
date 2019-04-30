<?php

namespace Test\Unit;

use Picqer\Barcode\BarcodeGenerator;
use Picqer\Barcode\Constants\BarcodeRender;
use Picqer\Barcode\Constants\BarcodeType;
use Test\BaseTestCase;

class PharmaCodeTwoTracksTest extends BaseTestCase
{
    const VALID_CODE = '081231723897';

    public function test_PharmaCodeTwoTracksGeneratesJPGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_PHARMA_CODE_TWO_TRACKS, BarcodeRender::RENDER_JPG);
        $generated = $generator->generate();

        $this->assertContains('JPEG', $generated);
    }

    public function test_PharmaCodeTwoTracksGeneratesPNGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_PHARMA_CODE_TWO_TRACKS, BarcodeRender::RENDER_PNG);
        $generated = $generator->generate();

        $this->assertEquals('PNG', substr($generated, 1, 3));
    }

    public function test_PharmaCodeTwoTracksGeneratesHTMLStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_PHARMA_CODE_TWO_TRACKS, BarcodeRender::RENDER_HTML);
        $generated = $generator->generate();

        $this->assertContains('<div', $generated);
    }

    public function test_PharmaCodeTwoTracksGeneratesSVGStructure()
    {
        $generator = new BarcodeGenerator(self::VALID_CODE, BarcodeType::TYPE_PHARMA_CODE_TWO_TRACKS, BarcodeRender::RENDER_SVG);
        $generated = $generator->generate();

        $this->assertContains('<svg', $generated);
    }
}
