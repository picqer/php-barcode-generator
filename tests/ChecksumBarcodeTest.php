<?php

use PHPUnit\Framework\TestCase;
use Picqer\Barcode\Exceptions\BarcodeException;
use Picqer\Barcode\Exceptions\InvalidCheckDigitException;
use Picqer\Barcode\Types\TypeEan13;
use Picqer\Barcode\Types\TypeInterface;
use Picqer\Barcode\Types\TypeITF14;

class ChecksumBarcodeTest extends TestCase
{
    private const VALID_BARCODES = [
        ['type' => TypeEan13::class, 'barcodes' => [
            '081231723897' => '0812317238973',
            '004900000463' => '0049000004632',
        ]],
        ['type' => TypeITF14::class, 'barcodes' => [
            '0001234560001' => '00012345600012',
            '0540014128876' => '05400141288766',
        ]],
    ];
    private const INVALID_BARCODES = [
        ['type' => TypeEan13::class, 'barcodes' => ['0812317238972', '0049000004633']],
        ['type' => TypeITF14::class, 'barcodes' => ['00012345600016', '05400141288762']],
    ];

    public function testAllValidBarcodeTypes()
    {
        foreach (self::VALID_BARCODES as $barcodeTestSet) {
            $barcodeType = $this->getBarcodeType($barcodeTestSet['type']);

            foreach ($barcodeTestSet['barcodes'] as $testBarcode => $validBarcode) {
                $this->assertEquals($validBarcode, $barcodeType->getBarcode($testBarcode)->getBarcode());
            }
        }
    }

    public function testAllInvalidBarcodeTypes()
    {
        foreach (self::INVALID_BARCODES as $barcodeTestSet) {
            $barcodeType = $this->getBarcodeType($barcodeTestSet['type']);

            foreach ($barcodeTestSet['barcodes'] as $invalidBarcode) {
                try {
                    $barcodeType->getBarcode($invalidBarcode)->getBarcode();
                } catch (BarcodeException $exception) {
                    $this->assertInstanceOf(InvalidCheckDigitException::class, $exception);
                    continue;
                }

                $this->assertTrue(false, sprintf('Exception was not thrown for barcode "%s".', $invalidBarcode));
            }
        }
    }

    private function getBarcodeType(string $typeClass): TypeInterface
    {
        return new $typeClass;
    }
}
