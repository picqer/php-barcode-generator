<?php

use PHPUnit\Framework\TestCase;
use Picqer\Barcode\Helpers\StringHelpers;

/*
 * Test all supported barcodes types, with as much different but supported input strings.
 * Verified files can be built with generate-verified-files.php file.
 * Only run that file if you added new types or new strings to test.
 *
 * We use SVG because that output is vector and should be the same on every host system.
 */

class VerifiedBarcodeTest extends TestCase
{
    public static array $supportedBarcodes = [
        ['type' => \Picqer\Barcode\Types\TypeCode39::class, 'barcodes' => ['1234567890ABC']],
        ['type' => \Picqer\Barcode\Types\TypeCode39Checksum::class, 'barcodes' => ['1234567890ABC']],
        ['type' => \Picqer\Barcode\Types\TypeCode39Extended::class, 'barcodes' => ['1234567890abcABC']],
        ['type' => \Picqer\Barcode\Types\TypeCode39ExtendedChecksum::class, 'barcodes' => ['1234567890abcABC']],
        ['type' => \Picqer\Barcode\Types\TypeCode93::class, 'barcodes' => ['1234567890abcABC']],
        ['type' => \Picqer\Barcode\Types\TypeStandard2of5::class, 'barcodes' => ['1234567890']],
        ['type' => \Picqer\Barcode\Types\TypeStandard2of5Checksum::class, 'barcodes' => ['1234567890']],
        ['type' => \Picqer\Barcode\Types\TypeInterleaved25::class, 'barcodes' => ['1234567890']],
        ['type' => \Picqer\Barcode\Types\TypeInterleaved25Checksum::class, 'barcodes' => ['1234567890']],
        ['type' => \Picqer\Barcode\Types\TypeEan13::class, 'barcodes' => ['081231723897', '0049000004632', '004900000463']],
        ['type' => \Picqer\Barcode\Types\TypeITF14::class, 'barcodes' => ['00012345600012', '05400141288766']],
        ['type' => \Picqer\Barcode\Types\TypeCode128::class, 'barcodes' => ['081231723897', '1234567890abcABC-283*33']],
        ['type' => \Picqer\Barcode\Types\TypeCode128A::class, 'barcodes' => ['1234567890']],
        ['type' => \Picqer\Barcode\Types\TypeCode128B::class, 'barcodes' => ['081231723897', '1234567890abcABC-283*33']],
        ['type' => \Picqer\Barcode\Types\TypeUpcExtension2::class, 'barcodes' => ['22']],
        ['type' => \Picqer\Barcode\Types\TypeUpcExtension5::class, 'barcodes' => ['1234567890abcABC-283*33']],
        ['type' => \Picqer\Barcode\Types\TypeEan8::class, 'barcodes' => ['1234568']],
        ['type' => \Picqer\Barcode\Types\TypeUpcA::class, 'barcodes' => ['123456789']],
        ['type' => \Picqer\Barcode\Types\TypeUpcE::class, 'barcodes' => ['123456789']],
        ['type' => \Picqer\Barcode\Types\TypeMsi::class, 'barcodes' => ['123456789']],
        ['type' => \Picqer\Barcode\Types\TypeMsiChecksum::class, 'barcodes' => ['123456789']],
        ['type' => \Picqer\Barcode\Types\TypePostnet::class, 'barcodes' => ['123456789']],
        ['type' => \Picqer\Barcode\Types\TypePlanet::class, 'barcodes' => ['123456789']],
        ['type' => \Picqer\Barcode\Types\TypeRms4cc::class, 'barcodes' => ['123456789']],
        ['type' => \Picqer\Barcode\Types\TypeKix::class, 'barcodes' => ['123456789']],
        ['type' => \Picqer\Barcode\Types\TypeIntelligentMailBarcode::class, 'barcodes' => ['123456789']],
        ['type' => \Picqer\Barcode\Types\TypeCodabar::class, 'barcodes' => ['123456789']],
        ['type' => \Picqer\Barcode\Types\TypeCode11::class, 'barcodes' => ['123456789']],
        ['type' => \Picqer\Barcode\Types\TypePharmacode::class, 'barcodes' => ['123456789']],
        ['type' => \Picqer\Barcode\Types\TypePharmacodeTwoCode::class, 'barcodes' => ['123456789']],
        ['type' => \Picqer\Barcode\Types\TypeTelepen::class, 'barcodes' => ['1234567890ASCD']],
        ['type' => \Picqer\Barcode\Types\TypeTelepenNumeric::class, 'barcodes' => ['1234567890']]
    ];

    public function testAllSupportedBarcodeTypes()
    {
        $renderer = new Picqer\Barcode\Renderers\SvgRenderer();

        foreach ($this::$supportedBarcodes as $barcodeTestSet) {
            foreach ($barcodeTestSet['barcodes'] as $barcodeText) {
                $barcode = (new $barcodeTestSet['type']())->getBarcode($barcodeText);
                $result = $renderer->render($barcode, $barcode->getWidth() * 2);

                $this->assertStringEqualsFile(
                    sprintf('tests/verified-files/%s.svg', StringHelpers::getSafeFilenameFrom($barcodeTestSet['type'] . '-' . $barcodeText)),
                    $result,
                    sprintf('%s x %s dynamic test failed', $barcodeTestSet['type'], $barcodeText)
                );
            }
        }
    }
}
