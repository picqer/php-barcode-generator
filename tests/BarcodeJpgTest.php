<?php

use PHPUnit\Framework\TestCase;

class BarcodeJpgTest extends TestCase
{
    public function test_jpg_barcode_generator_can_generate_code_128_barcode()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorJPG();
        $result = $generator->getBarcode('081231723897', $generator::TYPE_CODE_128);

        $imageInfo = getimagesizefromstring($result);

        $this->assertGreaterThan(1000, strlen($result));
        $this->assertEquals(202, $imageInfo[0]); // Image width
        $this->assertEquals(30, $imageInfo[1]); // Image height
        $this->assertEquals('image/jpeg', $imageInfo['mime']);
    }

    public function test_jpg_barcode_generator_can_generate_code_39_barcode()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorJPG();
        $result = $generator->getBarcode('081231723897', $generator::TYPE_CODE_39, 1);

        $imageInfo = getimagesizefromstring($result);

        $this->assertGreaterThan(1000, strlen($result));
        $this->assertEquals(224, $imageInfo[0]); // Image width
        $this->assertEquals(30, $imageInfo[1]); // Image height
        $this->assertEquals('image/jpeg', $imageInfo['mime']);
    }

    public function test_jpg_barcode_generator_can_use_different_height()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorJPG();
        $result = $generator->getBarcode('081231723897', $generator::TYPE_CODE_128, 2, 45);

        $imageInfo = getimagesizefromstring($result);

        $this->assertGreaterThan(1000, strlen($result));
        $this->assertEquals(202, $imageInfo[0]); // Image width
        $this->assertEquals(45, $imageInfo[1]); // Image height
        $this->assertEquals('image/jpeg', $imageInfo['mime']);
    }

    public function test_jpg_barcode_generator_can_use_different_width_factor()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorJPG();
        $result = $generator->getBarcode('081231723897', $generator::TYPE_CODE_128, 5);

        $imageInfo = getimagesizefromstring($result);

        $this->assertGreaterThan(1000, strlen($result));
        $this->assertEquals(505, $imageInfo[0]); // Image width
        $this->assertEquals('image/jpeg', $imageInfo['mime']);
    }
}
