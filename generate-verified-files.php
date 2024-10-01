<?php

require('vendor/autoload.php');

$svgRenderer = new Picqer\Barcode\Renderers\SvgRenderer();
$htmlRenderer = new Picqer\Barcode\Renderers\HtmlRenderer();
$dynamicHtmlRenderer = new Picqer\Barcode\Renderers\DynamicHtmlRenderer();

$typeEncoderEan13 = new \Picqer\Barcode\Types\TypeEan13();
$typeEncoderCode128 = new \Picqer\Barcode\Types\TypeCode128();
$typeEncoderIMB = new \Picqer\Barcode\Types\TypeIntelligentMailBarcode();

$barcode = $typeEncoderEan13->getBarcode('081231723897');
file_put_contents('tests/verified-files/081231723897-ean13.svg', $svgRenderer->render($barcode, $barcode->getWidth() * 2));
file_put_contents('tests/verified-files/081231723897-ean13-fractional-width.svg', $svgRenderer->render($barcode, $barcode->getWidth() * 0.25, 25.75));

$svgRendererRed = new Picqer\Barcode\Renderers\SvgRenderer();
$svgRendererRed->setBackgroundColor([255, 0, 0]);
file_put_contents('tests/verified-files/081231723897-ean13-red-background.svg', $svgRendererRed->render($barcode, $barcode->getWidth() * 2));

$barcode = $typeEncoderCode128->getBarcode('081231723897');
file_put_contents('tests/verified-files/081231723897-code128.html', $htmlRenderer->render($barcode, $barcode->getWidth() * 2));
$htmlRendererRed = new Picqer\Barcode\Renderers\HtmlRenderer();
$htmlRendererRed->setBackgroundColor([255, 0, 0]);
file_put_contents('tests/verified-files/081231723897-code128-red-background.html', $htmlRendererRed->render($barcode, $barcode->getWidth() * 2));

$barcode = $typeEncoderIMB->getBarcode('12345678903');
file_put_contents('tests/verified-files/12345678903-imb.html', $htmlRenderer->render($barcode, $barcode->getWidth() * 2));

$barcode = $typeEncoderCode128->getBarcode('081231723897');
file_put_contents('tests/verified-files/081231723897-dynamic-code128.html', $dynamicHtmlRenderer->render($barcode));

$barcode = $typeEncoderIMB->getBarcode('12345678903');
file_put_contents('tests/verified-files/12345678903-dynamic-imb.html', $dynamicHtmlRenderer->render($barcode));

$barcode = $typeEncoderEan13->getBarcode('0049000004632');
file_put_contents('tests/verified-files/0049000004632-ean13.svg', $svgRenderer->render($barcode, $barcode->getWidth() * 2));


// New style of verified files, defined in VerifiedBarcodeTest.php
require(__DIR__ . '/tests/VerifiedBarcodeTest.php');
$verifiedFiles = VerifiedBarcodeTest::$supportedBarcodes;

foreach ($verifiedFiles as $verifiedFile) {
    foreach ($verifiedFile['barcodes'] as $barcodeText) {
        $barcode = (new $verifiedFile['type']())->getBarcode($barcodeText);
        $result = $svgRenderer->render($barcode, $barcode->getWidth() * 2);

        file_put_contents('tests/verified-files/' . Picqer\Barcode\Helpers\StringHelpers::getSafeFilenameFrom($verifiedFile['type'] . '-' . $barcodeText) . '.svg', $result);
    }
}
