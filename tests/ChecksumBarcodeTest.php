<?php

use PHPUnit\Framework\TestCase;
use Picqer\Barcode\Types\TypeEan13;
use Picqer\Barcode\Types\TypeInterface;
use Picqer\Barcode\Types\TypeITF14;

class ChecksumBarcodeTest extends TestCase
{
    public static $supportedBarcodes = [
        ['type' => TypeEan13::class, 'barcodes' => [
            '081231723897' => '0812317238973',
            '004900000463' => '0049000004632',
        ]],
        ['type' => TypeITF14::class, 'barcodes' => [
            '0001234560001' => '00012345600012',
            '0540014128876' => '05400141288766',
        ]],
    ];

    public function testAllSupportedBarcodeTypes()
    {
        foreach ($this::$supportedBarcodes as $barcodeTestSet) {
            $barcodeType = $this->getBarcodeType($barcodeTestSet['type']);

            foreach ($barcodeTestSet['barcodes'] as $testBarcode => $expectedBarcode) {
                $this->assertEquals($expectedBarcode, $barcodeType->getBarcodeData($testBarcode)->getBarcode());
            }
        }
    }


    private function getBarcodeType(string $typeClass): TypeInterface
    {
        return new $typeClass;
    }
}
