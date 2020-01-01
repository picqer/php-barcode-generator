<?php

namespace Picqer\Barcode\Helpers;

class OldBarcodeArrayConverter
{
    public static function convert($oldBarcodeArray): array
    {
        $newBarcodeArray = [];
        $newBarcodeArray['code'] = $oldBarcodeArray['code'];
        $newBarcodeArray['maxWidth'] = $oldBarcodeArray['maxw'];
        $newBarcodeArray['maxHeight'] = $oldBarcodeArray['maxh'];
        $newBarcodeArray['bars'] = [];

        foreach ($oldBarcodeArray['bcode'] as $oldbar) {
            $newBar = [];
            $newBar['width'] = $oldbar['w'];
            $newBar['height'] = $oldbar['h'];
            $newBar['positionVertical'] = $oldbar['p'];
            $newBar['drawBar'] = $oldbar['t'];
            $newBar['drawSpacing'] = ! $oldbar['t'];

            $newBarcodeArray['bars'][] = $newBar;
        }

        return $newBarcodeArray;
    }
}