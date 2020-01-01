<?php

/**
 * General PHP Barcode Generator
 *
 * @author Casper Bakker - picqer.com
 * Based on TCPDF Barcode Generator
 */

// Copyright (C) 2002-2015 Nicola Asuni - Tecnick.com LTD
//
// This file was part of TCPDF software library.
//
// TCPDF is free software: you can redistribute it and/or modify it
// under the terms of the GNU Lesser General Public License as
// published by the Free Software Foundation, either version 3 of the
// License, or (at your option) any later version.
//
// TCPDF is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// See the GNU Lesser General Public License for more details.
//
// You should have received a copy of the License
// along with TCPDF. If not, see
// <http://www.tecnick.com/pagefiles/tcpdf/LICENSE.TXT>.
//
// See LICENSE.TXT file for more information.

namespace Picqer\Barcode;

use Picqer\Barcode\Exceptions\InvalidCharacterException;
use Picqer\Barcode\Exceptions\InvalidCheckDigitException;
use Picqer\Barcode\Exceptions\InvalidFormatException;
use Picqer\Barcode\Exceptions\InvalidLengthException;
use Picqer\Barcode\Exceptions\UnknownTypeException;
use Picqer\Barcode\Helpers\BinarySequenceConverter;
use Picqer\Barcode\Helpers\OldBarcodeArrayConverter;
use Picqer\Barcode\Types\TypeCodabar;
use Picqer\Barcode\Types\TypeCode11;
use Picqer\Barcode\Types\TypeCode39;
use Picqer\Barcode\Types\TypeCode39Checksum;
use Picqer\Barcode\Types\TypeCode39Extended;
use Picqer\Barcode\Types\TypeCode39ExtendedChecksum;
use Picqer\Barcode\Types\TypeCode93;
use Picqer\Barcode\Types\TypeEan13;
use Picqer\Barcode\Types\TypeEan8;
use Picqer\Barcode\Types\TypeIntelligentMailBarcode;
use Picqer\Barcode\Types\TypeInterleaved25;
use Picqer\Barcode\Types\TypeInterleaved25Checksum;
use Picqer\Barcode\Types\TypeKix;
use Picqer\Barcode\Types\TypeMsi;
use Picqer\Barcode\Types\TypeMsiChecksum;
use Picqer\Barcode\Types\TypePharmacode;
use Picqer\Barcode\Types\TypePharmacodeTwoCode;
use Picqer\Barcode\Types\TypePlanet;
use Picqer\Barcode\Types\TypePostnet;
use Picqer\Barcode\Types\TypeRms4cc;
use Picqer\Barcode\Types\TypeStandard2of5;
use Picqer\Barcode\Types\TypeStandard2of5Checksum;
use Picqer\Barcode\Types\TypeUpcA;
use Picqer\Barcode\Types\TypeUpcE;
use Picqer\Barcode\Types\TypeUpcExtension2;
use Picqer\Barcode\Types\TypeUpcExtension5;

abstract class BarcodeGenerator
{
    const TYPE_CODE_39 = 'C39'; // CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9.
    const TYPE_CODE_39_CHECKSUM = 'C39+';  // CODE 39 with checksum
    const TYPE_CODE_39E = 'C39E'; // CODE 39 EXTENDED
    const TYPE_CODE_39E_CHECKSUM = 'C39E+'; // CODE 39 EXTENDED + CHECKSUM
    const TYPE_CODE_93 = 'C93'; // CODE 93 - USS-93
    const TYPE_STANDARD_2_5 = 'S25'; // Standard 2 of 5
    const TYPE_STANDARD_2_5_CHECKSUM = 'S25+'; // Standard 2 of 5 + CHECKSUM
    const TYPE_INTERLEAVED_2_5 = 'I25'; // Interleaved 2 of 5
    const TYPE_INTERLEAVED_2_5_CHECKSUM = 'I25+'; // Interleaved 2 of 5 + CHECKSUM
    const TYPE_CODE_128 = 'C128';
    const TYPE_CODE_128_A = 'C128A';
    const TYPE_CODE_128_B = 'C128B';
    const TYPE_CODE_128_C = 'C128C';
    const TYPE_EAN_2 = 'EAN2'; // 2-Digits UPC-Based Extention
    const TYPE_EAN_5 = 'EAN5'; // 5-Digits UPC-Based Extention
    const TYPE_EAN_8 = 'EAN8';
    const TYPE_EAN_13 = 'EAN13';
    const TYPE_UPC_A = 'UPCA';
    const TYPE_UPC_E = 'UPCE';
    const TYPE_MSI = 'MSI'; // MSI (Variation of Plessey code)
    const TYPE_MSI_CHECKSUM = 'MSI+'; // MSI + CHECKSUM (modulo 11)
    const TYPE_POSTNET = 'POSTNET';
    const TYPE_PLANET = 'PLANET';
    const TYPE_RMS4CC = 'RMS4CC'; // RMS4CC (Royal Mail 4-state Customer Code) - CBC (Customer Bar Code)
    const TYPE_KIX = 'KIX'; // KIX (Klant index - Customer index)
    const TYPE_IMB = 'IMB'; // IMB - Intelligent Mail Barcode - Onecode - USPS-B-3200
    const TYPE_CODABAR = 'CODABAR';
    const TYPE_CODE_11 = 'CODE11';
    const TYPE_PHARMA_CODE = 'PHARMA';
    const TYPE_PHARMA_CODE_TWO_TRACKS = 'PHARMA2T';

    protected function getBarcodeData(string $code, string $type): array
    {
        switch (strtoupper($type)) {
            case self::TYPE_CODE_39:
                $barcodeDataBuilder = new TypeCode39();
                break;

            case self::TYPE_CODE_39_CHECKSUM:
                $barcodeDataBuilder = new TypeCode39Checksum();
                break;

            case self::TYPE_CODE_39E:
                $barcodeDataBuilder = new TypeCode39Extended();
                break;

            case self::TYPE_CODE_39E_CHECKSUM:
                $barcodeDataBuilder = new TypeCode39ExtendedChecksum();
                break;

            case self::TYPE_CODE_93:
                $barcodeDataBuilder = new TypeCode93();
                break;

            case self::TYPE_STANDARD_2_5:
                $barcodeDataBuilder = new TypeStandard2of5();
                break;

            case self::TYPE_STANDARD_2_5_CHECKSUM:
                $barcodeDataBuilder = new TypeStandard2of5Checksum();
                break;

            case self::TYPE_INTERLEAVED_2_5:
                $barcodeDataBuilder = new TypeInterleaved25();
                break;

            case self::TYPE_INTERLEAVED_2_5_CHECKSUM:
                $barcodeDataBuilder = new TypeInterleaved25Checksum();
                break;

            case self::TYPE_CODE_128:
                $arrcode = $this->barcode_c128($code, '');
                break;

            case self::TYPE_CODE_128_A:
                $arrcode = $this->barcode_c128($code, 'A');
                break;

            case self::TYPE_CODE_128_B:
                $arrcode = $this->barcode_c128($code, 'B');
                break;

            case self::TYPE_CODE_128_C:
                $arrcode = $this->barcode_c128($code, 'C');
                break;

            case self::TYPE_EAN_2:
                $barcodeDataBuilder = new TypeUpcExtension2();
                break;

            case self::TYPE_EAN_5:
                $barcodeDataBuilder = new TypeUpcExtension5();
                break;

            case self::TYPE_EAN_8:
                $barcodeDataBuilder = new TypeEan8();
                break;

            case self::TYPE_EAN_13:
                $barcodeDataBuilder = new TypeEan13();
                break;

            case self::TYPE_UPC_A:
                $barcodeDataBuilder = new TypeUpcA();
                break;

            case self::TYPE_UPC_E:
                $barcodeDataBuilder = new TypeUpcE();
                break;

            case self::TYPE_MSI:
                $barcodeDataBuilder = new TypeMsi();
                break;

            case self::TYPE_MSI_CHECKSUM:
                $barcodeDataBuilder = new TypeMsiChecksum();
                break;

            case self::TYPE_POSTNET:
                $barcodeDataBuilder = new TypePostnet();
                break;

            case self::TYPE_PLANET:
                $barcodeDataBuilder = new TypePlanet();
                break;

            case self::TYPE_RMS4CC:
                $barcodeDataBuilder = new TypeRms4cc();
                break;

            case self::TYPE_KIX:
                $barcodeDataBuilder = new TypeKix();
                break;

            case self::TYPE_IMB:
                $barcodeDataBuilder = new TypeIntelligentMailBarcode();
                break;

            case self::TYPE_CODABAR:
                $barcodeDataBuilder = new TypeCodabar();
                break;

            case self::TYPE_CODE_11:
                $barcodeDataBuilder = new TypeCode11();
                break;

            case self::TYPE_PHARMA_CODE:
                $barcodeDataBuilder = new TypePharmacode();
                break;

            case self::TYPE_PHARMA_CODE_TWO_TRACKS:
                $barcodeDataBuilder = new TypePharmacodeTwoCode();
                break;

            default:
                throw new UnknownTypeException();
        }

        if (! isset($arrcode) && isset($barcodeDataBuilder)) {
            $arrcode = $barcodeDataBuilder->getBarcodeData($code);
        }

        if ( ! isset($arrcode['maxWidth'])) {
            return OldBarcodeArrayConverter::convert($arrcode);
        }

        return $arrcode;
    }

    /**
     * C128 barcodes.
     * Very capable code, excellent density, high reliability; in very wide use world-wide
     *
     * @param $code (string) code to represent.
     * @param $type (string) barcode type: A, B, C or empty for automatic switch (AUTO mode)
     * @return array barcode representation.
     * @protected
     */
    protected function barcode_c128($code, $type = '')
    {
        $chr = array(
            '212222', /* 00 */
            '222122', /* 01 */
            '222221', /* 02 */
            '121223', /* 03 */
            '121322', /* 04 */
            '131222', /* 05 */
            '122213', /* 06 */
            '122312', /* 07 */
            '132212', /* 08 */
            '221213', /* 09 */
            '221312', /* 10 */
            '231212', /* 11 */
            '112232', /* 12 */
            '122132', /* 13 */
            '122231', /* 14 */
            '113222', /* 15 */
            '123122', /* 16 */
            '123221', /* 17 */
            '223211', /* 18 */
            '221132', /* 19 */
            '221231', /* 20 */
            '213212', /* 21 */
            '223112', /* 22 */
            '312131', /* 23 */
            '311222', /* 24 */
            '321122', /* 25 */
            '321221', /* 26 */
            '312212', /* 27 */
            '322112', /* 28 */
            '322211', /* 29 */
            '212123', /* 30 */
            '212321', /* 31 */
            '232121', /* 32 */
            '111323', /* 33 */
            '131123', /* 34 */
            '131321', /* 35 */
            '112313', /* 36 */
            '132113', /* 37 */
            '132311', /* 38 */
            '211313', /* 39 */
            '231113', /* 40 */
            '231311', /* 41 */
            '112133', /* 42 */
            '112331', /* 43 */
            '132131', /* 44 */
            '113123', /* 45 */
            '113321', /* 46 */
            '133121', /* 47 */
            '313121', /* 48 */
            '211331', /* 49 */
            '231131', /* 50 */
            '213113', /* 51 */
            '213311', /* 52 */
            '213131', /* 53 */
            '311123', /* 54 */
            '311321', /* 55 */
            '331121', /* 56 */
            '312113', /* 57 */
            '312311', /* 58 */
            '332111', /* 59 */
            '314111', /* 60 */
            '221411', /* 61 */
            '431111', /* 62 */
            '111224', /* 63 */
            '111422', /* 64 */
            '121124', /* 65 */
            '121421', /* 66 */
            '141122', /* 67 */
            '141221', /* 68 */
            '112214', /* 69 */
            '112412', /* 70 */
            '122114', /* 71 */
            '122411', /* 72 */
            '142112', /* 73 */
            '142211', /* 74 */
            '241211', /* 75 */
            '221114', /* 76 */
            '413111', /* 77 */
            '241112', /* 78 */
            '134111', /* 79 */
            '111242', /* 80 */
            '121142', /* 81 */
            '121241', /* 82 */
            '114212', /* 83 */
            '124112', /* 84 */
            '124211', /* 85 */
            '411212', /* 86 */
            '421112', /* 87 */
            '421211', /* 88 */
            '212141', /* 89 */
            '214121', /* 90 */
            '412121', /* 91 */
            '111143', /* 92 */
            '111341', /* 93 */
            '131141', /* 94 */
            '114113', /* 95 */
            '114311', /* 96 */
            '411113', /* 97 */
            '411311', /* 98 */
            '113141', /* 99 */
            '114131', /* 100 */
            '311141', /* 101 */
            '411131', /* 102 */
            '211412', /* 103 START A */
            '211214', /* 104 START B */
            '211232', /* 105 START C */
            '233111', /* STOP */
            '200000'  /* END */
        );
        // ASCII characters for code A (ASCII 00 - 95)
        $keys_a = ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_';
        $keys_a .= chr(0) . chr(1) . chr(2) . chr(3) . chr(4) . chr(5) . chr(6) . chr(7) . chr(8) . chr(9);
        $keys_a .= chr(10) . chr(11) . chr(12) . chr(13) . chr(14) . chr(15) . chr(16) . chr(17) . chr(18) . chr(19);
        $keys_a .= chr(20) . chr(21) . chr(22) . chr(23) . chr(24) . chr(25) . chr(26) . chr(27) . chr(28) . chr(29);
        $keys_a .= chr(30) . chr(31);
        // ASCII characters for code B (ASCII 32 - 127)
        $keys_b = ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~' . chr(127);
        // special codes
        $fnc_a = array(241 => 102, 242 => 97, 243 => 96, 244 => 101);
        $fnc_b = array(241 => 102, 242 => 97, 243 => 96, 244 => 100);
        // array of symbols
        $code_data = array();
        // length of the code
        $len = strlen($code);
        switch (strtoupper($type)) {
            case 'A': { // MODE A
                $startid = 103;
                for ($i = 0; $i < $len; ++$i) {
                    $char = $code[$i];
                    $char_id = ord($char);
                    if (($char_id >= 241) AND ($char_id <= 244)) {
                        $code_data[] = $fnc_a[$char_id];
                    } elseif (($char_id >= 0) AND ($char_id <= 95)) {
                        $code_data[] = strpos($keys_a, $char);
                    } else {
                        throw new InvalidCharacterException('Char ' . $char . ' is unsupported');
                    }
                }
                break;
            }
            case 'B': { // MODE B
                $startid = 104;
                for ($i = 0; $i < $len; ++$i) {
                    $char = $code[$i];
                    $char_id = ord($char);
                    if (($char_id >= 241) AND ($char_id <= 244)) {
                        $code_data[] = $fnc_b[$char_id];
                    } elseif (($char_id >= 32) AND ($char_id <= 127)) {
                        $code_data[] = strpos($keys_b, $char);
                    } else {
                        throw new InvalidCharacterException('Char ' . $char . ' is unsupported');
                    }
                }
                break;
            }
            case 'C': { // MODE C
                $startid = 105;
                if (ord($code[0]) == 241) {
                    $code_data[] = 102;
                    $code = substr($code, 1);
                    --$len;
                }
                if (($len % 2) != 0) {
                    throw new InvalidLengthException('Length must be even');
                }
                for ($i = 0; $i < $len; $i += 2) {
                    $chrnum = $code[$i] . $code[$i + 1];
                    if (preg_match('/([0-9]{2})/', $chrnum) > 0) {
                        $code_data[] = intval($chrnum);
                    } else {
                        throw new InvalidCharacterException();
                    }
                }
                break;
            }
            default: { // MODE AUTO
                // split code into sequences
                $sequence = array();
                // get numeric sequences (if any)
                $numseq = array();
                preg_match_all('/([0-9]{4,})/', $code, $numseq, PREG_OFFSET_CAPTURE);
                if (isset($numseq[1]) AND ! empty($numseq[1])) {
                    $end_offset = 0;
                    foreach ($numseq[1] as $val) {
                        $offset = $val[1];
                        
                        // numeric sequence
                        $slen = strlen($val[0]);
                        if (($slen % 2) != 0) {
                            // the length must be even
                            ++$offset;
                            $val[0] = substr($val[0],1);
                        }
                        
                        if ($offset > $end_offset) {
                            // non numeric sequence
                            $sequence = array_merge($sequence,
                                $this->get128ABsequence(substr($code, $end_offset, ($offset - $end_offset))));
                        }
                        // numeric sequence fallback
                        $slen = strlen($val[0]);
                        if (($slen % 2) != 0) {
                            // the length must be even
                            --$slen;
                        }
                        $sequence[] = array('C', substr($code, $offset, $slen), $slen);
                        $end_offset = $offset + $slen;
                    }
                    if ($end_offset < $len) {
                        $sequence = array_merge($sequence, $this->get128ABsequence(substr($code, $end_offset)));
                    }
                } else {
                    // text code (non C mode)
                    $sequence = array_merge($sequence, $this->get128ABsequence($code));
                }
                // process the sequence
                foreach ($sequence as $key => $seq) {
                    switch ($seq[0]) {
                        case 'A': {
                            if ($key == 0) {
                                $startid = 103;
                            } elseif ($sequence[($key - 1)][0] != 'A') {
                                if (($seq[2] == 1) AND ($key > 0) AND ($sequence[($key - 1)][0] == 'B') AND ( ! isset($sequence[($key - 1)][3]))) {
                                    // single character shift
                                    $code_data[] = 98;
                                    // mark shift
                                    $sequence[$key][3] = true;
                                } elseif ( ! isset($sequence[($key - 1)][3])) {
                                    $code_data[] = 101;
                                }
                            }
                            for ($i = 0; $i < $seq[2]; ++$i) {
                                $char = $seq[1][$i];
                                $char_id = ord($char);
                                if (($char_id >= 241) AND ($char_id <= 244)) {
                                    $code_data[] = $fnc_a[$char_id];
                                } else {
                                    $code_data[] = strpos($keys_a, $char);
                                }
                            }
                            break;
                        }
                        case 'B': {
                            if ($key == 0) {
                                $tmpchr = ord($seq[1][0]);
                                if (($seq[2] == 1) AND ($tmpchr >= 241) AND ($tmpchr <= 244) AND isset($sequence[($key + 1)]) AND ($sequence[($key + 1)][0] != 'B')) {
                                    switch ($sequence[($key + 1)][0]) {
                                        case 'A': {
                                            $startid = 103;
                                            $sequence[$key][0] = 'A';
                                            $code_data[] = $fnc_a[$tmpchr];
                                            break;
                                        }
                                        case 'C': {
                                            $startid = 105;
                                            $sequence[$key][0] = 'C';
                                            $code_data[] = $fnc_a[$tmpchr];
                                            break;
                                        }
                                    }
                                    break;
                                } else {
                                    $startid = 104;
                                }
                            } elseif ($sequence[($key - 1)][0] != 'B') {
                                if (($seq[2] == 1) AND ($key > 0) AND ($sequence[($key - 1)][0] == 'A') AND ( ! isset($sequence[($key - 1)][3]))) {
                                    // single character shift
                                    $code_data[] = 98;
                                    // mark shift
                                    $sequence[$key][3] = true;
                                } elseif ( ! isset($sequence[($key - 1)][3])) {
                                    $code_data[] = 100;
                                }
                            }
                            for ($i = 0; $i < $seq[2]; ++$i) {
                                $char = $seq[1][$i];
                                $char_id = ord($char);
                                if (($char_id >= 241) AND ($char_id <= 244)) {
                                    $code_data[] = $fnc_b[$char_id];
                                } else {
                                    $code_data[] = strpos($keys_b, $char);
                                }
                            }
                            break;
                        }
                        case 'C': {
                            if ($key == 0) {
                                $startid = 105;
                            } elseif ($sequence[($key - 1)][0] != 'C') {
                                $code_data[] = 99;
                            }
                            for ($i = 0; $i < $seq[2]; $i += 2) {
                                $chrnum = $seq[1][$i] . $seq[1][$i + 1];
                                $code_data[] = intval($chrnum);
                            }
                            break;
                        }
                    }
                }
            }
        }
        // calculate check character
        $sum = $startid;
        foreach ($code_data as $key => $val) {
            $sum += ($val * ($key + 1));
        }
        // add check character
        $code_data[] = ($sum % 103);
        // add stop sequence
        $code_data[] = 106;
        $code_data[] = 107;
        // add start code at the beginning
        array_unshift($code_data, $startid);
        // build barcode array
        $bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());
        foreach ($code_data as $val) {
            $seq = $chr[$val];
            for ($j = 0; $j < 6; ++$j) {
                if (($j % 2) == 0) {
                    $t = true; // bar
                } else {
                    $t = false; // space
                }
                $w = $seq[$j];
                $bararray['bcode'][] = array('t' => $t, 'w' => $w, 'h' => 1, 'p' => 0);
                $bararray['maxw'] += $w;
            }
        }

        return $bararray;
    }

    /**
     * Split text code in A/B sequence for 128 code
     *
     * @param $code (string) code to split.
     * @return array sequence
     * @protected
     */
    protected function get128ABsequence($code)
    {
        $len = strlen($code);
        $sequence = array();
        // get A sequences (if any)
        $numseq = array();
        preg_match_all('/([\x00-\x1f])/', $code, $numseq, PREG_OFFSET_CAPTURE);
        if (isset($numseq[1]) AND ! empty($numseq[1])) {
            $end_offset = 0;
            foreach ($numseq[1] as $val) {
                $offset = $val[1];
                if ($offset > $end_offset) {
                    // B sequence
                    $sequence[] = array(
                        'B',
                        substr($code, $end_offset, ($offset - $end_offset)),
                        ($offset - $end_offset)
                    );
                }
                // A sequence
                $slen = strlen($val[0]);
                $sequence[] = array('A', substr($code, $offset, $slen), $slen);
                $end_offset = $offset + $slen;
            }
            if ($end_offset < $len) {
                $sequence[] = array('B', substr($code, $end_offset), ($len - $end_offset));
            }
        } else {
            // only B sequence
            $sequence[] = array('B', $code, $len);
        }

        return $sequence;
    }

    /**
     * IMB - Intelligent Mail Barcode - Onecode - USPS-B-3200
     *
     * @param $code (string) pre-formatted IMB barcode (65 chars "FADT")
     * @return array barcode representation.
     * @protected
     */
    protected function barcode_imb_pre($code)
    {
        if ( ! preg_match('/^[fadtFADT]{65}$/', $code) == 1) {
            throw new InvalidFormatException();
        }
        $characters = str_split(strtolower($code), 1);
        // build bars
        $k = 0;
        $bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 3, 'bcode' => array());
        for ($i = 0; $i < 65; ++$i) {
            switch ($characters[$i]) {
                case 'f': {
                    // full bar
                    $p = 0;
                    $h = 3;
                    break;
                }
                case 'a': {
                    // ascender
                    $p = 0;
                    $h = 2;
                    break;
                }
                case 'd': {
                    // descender
                    $p = 1;
                    $h = 2;
                    break;
                }
                case 't': {
                    // tracker (short)
                    $p = 1;
                    $h = 1;
                    break;
                }
            }
            $bararray['bcode'][$k++] = array('t' => 1, 'w' => 1, 'h' => $h, 'p' => $p);
            $bararray['bcode'][$k++] = array('t' => 0, 'w' => 1, 'h' => 2, 'p' => 0);
            $bararray['maxw'] += 2;
        }
        unset($bararray['bcode'][($k - 1)]);
        --$bararray['maxw'];

        return $bararray;
    }
}
