<?php

namespace Picqer\Barcode\Types;

use Picqer\Barcode\Constants\BarcodeType;
use Picqer\Barcode\Exceptions\UnknownTypeException;

class BarcodeTypeFactory
{
    /**
     * Generates a new Barcode Type.
     *
     * @param string $code
     * @param string $type
     *
     * @return BarcodeTypeInterface
     */
    public function generateBarcodeType(string $code, string $type): BarcodeTypeInterface
    {
        switch (strtoupper($type)) {
            case BarcodeType::TYPE_CODE_39:  // CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9.
                return new Code39($code, false, false);
                break;

            case BarcodeType::TYPE_CODE_39_CHECKSUM:  // CODE 39 with checksum
                return new Code39($code, true, false);
                break;

            case BarcodeType::TYPE_CODE_39E:  // CODE 39 EXTENDED
                return new Code39($code, false, true);
                break;

            case BarcodeType::TYPE_CODE_39E_CHECKSUM:  // CODE 39 EXTENDED + CHECKSUM
                return new Code39($code, true, true);
                break;

            case BarcodeType::TYPE_CODE_93:  // CODE 93 - USS-93
                return new Code93($code);
                break;

            case BarcodeType::TYPE_STANDARD_2_5:  // Standard 2 of 5
                return new S25($code, false);
                break;

            case BarcodeType::TYPE_STANDARD_2_5_CHECKSUM:  // Standard 2 of 5 + CHECKSUM
                return new S25($code, true);
                break;

            case BarcodeType::TYPE_INTERLEAVED_2_5:  // Interleaved 2 of 5
                return new I25($code, false);
                break;

            case BarcodeType::TYPE_INTERLEAVED_2_5_CHECKSUM:  // Interleaved 2 of 5 + CHECKSUM
                return new I25($code, true);
                break;

            case BarcodeType::TYPE_CODE_128:  // CODE 128
                return new C128($code);
                break;

            case BarcodeType::TYPE_CODE_128_A:  // CODE 128 A
                return new C128($code, 'A');
                break;

            case BarcodeType::TYPE_CODE_128_B:  // CODE 128 B
                return new C128($code, 'B');
                break;

            case BarcodeType::TYPE_CODE_128_C:  // CODE 128 C
                return new C128($code, 'C');
                break;

            case BarcodeType::TYPE_EAN_2:  // 2-Digits UPC-Based Extention
                return new EanExt($code, 2);
                break;

            case BarcodeType::TYPE_EAN_5:  // 5-Digits UPC-Based Extention
                return new EanExt($code, 5);
                break;

            case BarcodeType::TYPE_EAN_8:  // EAN 8
                return new EanUpc($code, 8);
                break;

            case BarcodeType::TYPE_EAN_13:  // EAN 13
                return new EanUpc($code, 13);
                break;

            case BarcodeType::TYPE_UPC_A:  // UPC-A
                return new EanUpc($code, 12);
                break;

            case BarcodeType::TYPE_UPC_E:  // UPC-E
                return new EanUpc($code, 6);
                break;

            case BarcodeType::TYPE_MSI:  // MSI (Variation of Plessey code)
                return new MSI($code);
                break;

            case BarcodeType::TYPE_MSI_CHECKSUM:  // MSI + CHECKSUM (modulo 11)
                return new MSI($code, true);
                break;

            case BarcodeType::TYPE_POSTNET:  // POSTNET
                return new PostnetPlanet($code);
                break;

            case BarcodeType::TYPE_PLANET:  // PLANET
                return new PostnetPlanet($code, true);
                break;

            case BarcodeType::TYPE_RMS4CC:  // RMS4CC (Royal Mail 4-state Customer Code) - CBC (Customer Bar Code)
                return new RMS4CC($code);
                break;

            case BarcodeType::TYPE_KIX:  // KIX (Klant index - Customer index)
                return new RMS4CC($code, true);
                break;

            case BarcodeType::TYPE_IMB:  // IMB - Intelligent Mail Barcode - Onecode - USPS-B-3200
                return new IMB($code);
                break;

            case BarcodeType::TYPE_CODABAR:  // CODABAR
                return new Codabar($code);
                break;

            case BarcodeType::TYPE_CODE_11:  // CODE 11
                return new Code11($code);
                break;

            case BarcodeType::TYPE_PHARMA_CODE:  // PHARMACODE
                return new PharmaCode($code);
                break;

            case BarcodeType::TYPE_PHARMA_CODE_TWO_TRACKS:  // PHARMACODE TWO-TRACKS
                return new PharmaCodeTwoTracks($code);
                break;

            default:
                throw new UnknownTypeException();
                break;
        }
    }
}
