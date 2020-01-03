<?php

namespace Picqer\Barcode\Helpers;

class OldBarcodeArrayConverter
{
    public static function convert(array $oldBarcodeArray): array
    {
        $newBarcodeArray = [];
        $newBarcodeArray['code'] = (string)$oldBarcodeArray['code'];
        $newBarcodeArray['maxWidth'] = (int)$oldBarcodeArray['maxw'];
        $newBarcodeArray['maxHeight'] = (int)$oldBarcodeArray['maxh'];
        $newBarcodeArray['bars'] = [];

        foreach ($oldBarcodeArray['bcode'] as $oldbar) {
            $newBar = [];
            $newBar['width'] = (int)$oldbar['w'];
            $newBar['height'] = (int)$oldbar['h'];
            $newBar['positionVertical'] = (int)$oldbar['p'];
            $newBar['drawBar'] = (bool)$oldbar['t'];
            $newBar['drawSpacing'] = ! (bool)$oldbar['t'];

            $newBarcodeArray['bars'][] = $newBar;
        }

        return $newBarcodeArray;
    }
}
