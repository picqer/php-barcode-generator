<?php

use PHPUnit\Framework\TestCase;

/*
 * Test all supported barcodes types, with as much different but supported input strings.
 * Verified files can be build with generate-verified-files.php file.
 * Only run that file if you added new types or new strings to test.
 *
 * We use SVG because that output is vector and should be the same on every host system.
 */

class VerifiedBarcodeTest extends TestCase
{
    public function testAllSupportedBarcodeTypes()
    {
        $generator = new Picqer\Barcode\BarcodeGeneratorSVG();

        foreach ($this::$supportedBarcodes as $barcodeTestSet) {
            foreach ($barcodeTestSet['barcodes'] as $barcode) {
                $result = $generator->getBarcode($barcode, $barcodeTestSet['type']);

                $this->assertStringEqualsFile(sprintf('tests/verified-files/%s-%s.svg', $barcodeTestSet['type'], $barcode), $result);
            }
        }
    }

    public static $supportedBarcodes = [
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_CODE_39, 'barcodes' => ['1234567890abcABC']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_CODE_39_CHECKSUM, 'barcodes' => ['1234567890abcABC']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_CODE_39E_CHECKSUM, 'barcodes' => ['1234567890abcABC']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_CODE_93, 'barcodes' => ['1234567890abcABC']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_STANDARD_2_5, 'barcodes' => ['1234567890']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_STANDARD_2_5_CHECKSUM, 'barcodes' => ['1234567890']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_INTERLEAVED_2_5, 'barcodes' => ['1234567890']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_INTERLEAVED_2_5_CHECKSUM, 'barcodes' => ['1234567890']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_EAN_13, 'barcodes' => ['081231723897', '0049000004632', '004900000463']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_CODE_128, 'barcodes' => ['081231723897', '1234567890abcABC-283*33']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_CODE_128_A, 'barcodes' => ['1234567890']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_CODE_128_B, 'barcodes' => ['081231723897', '1234567890abcABC-283*33']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_EAN_2, 'barcodes' => ['1234567890abcABC-283*33']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_EAN_5, 'barcodes' => ['1234567890abcABC-283*33']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_EAN_8, 'barcodes' => ['1234568']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_UPC_A, 'barcodes' => ['123456789']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_UPC_E, 'barcodes' => ['123456789']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_MSI, 'barcodes' => ['123456789']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_MSI_CHECKSUM, 'barcodes' => ['123456789']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_POSTNET, 'barcodes' => ['123456789']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_PLANET, 'barcodes' => ['123456789']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_RMS4CC, 'barcodes' => ['123456789']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_KIX, 'barcodes' => ['123456789']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_IMB, 'barcodes' => ['123456789']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_CODABAR, 'barcodes' => ['123456789']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_CODE_11, 'barcodes' => ['123456789']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_PHARMA_CODE, 'barcodes' => ['123456789']],
        ['type' => \Picqer\Barcode\BarcodeGenerator::TYPE_PHARMA_CODE_TWO_TRACKS, 'barcodes' => ['123456789']],
    ];
}