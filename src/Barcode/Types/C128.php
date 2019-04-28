<?php

namespace Picqer\Barcode\Types;

class C128 extends BarcodeTypeAbstract implements BarcodeTypeInterface
{
    /** @var string */
    protected $codeType;

    public function __construct(string $code, string $codeType = '')
    {
        parent::__construct($code);
        $this->codeType = $codeType;
    }

    public function generate(): array
    {
        return $this->convertBarcodeArrayToNewStyle($this->barcode_c128($this->code, $this->codeType));
    }

    /**
     * C128 barcodes.
     * Very capable code, excellent density, high reliability; in very wide use world-wide.
     *
     * @param string $code code to represent
     * @param string $type barcode type: A, B, C or empty for automatic switch (AUTO mode)
     *
     * @return array barcode representation
     */
    protected function barcode_c128(string $code, string $type = ''): array
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
            '200000',  /* END */
        );
        // ASCII characters for code A (ASCII 00 - 95)
        $keys_a = ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_';
        $keys_a .= chr(0).chr(1).chr(2).chr(3).chr(4).chr(5).chr(6).chr(7).chr(8).chr(9);
        $keys_a .= chr(10).chr(11).chr(12).chr(13).chr(14).chr(15).chr(16).chr(17).chr(18).chr(19);
        $keys_a .= chr(20).chr(21).chr(22).chr(23).chr(24).chr(25).chr(26).chr(27).chr(28).chr(29);
        $keys_a .= chr(30).chr(31);
        // ASCII characters for code B (ASCII 32 - 127)
        $keys_b = ' !"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\\]^_`abcdefghijklmnopqrstuvwxyz{|}~'.chr(127);
        // special codes
        $fnc_a = array(241 => 102, 242 => 97, 243 => 96, 244 => 101);
        $fnc_b = array(241 => 102, 242 => 97, 243 => 96, 244 => 100);
        // array of symbols
        $code_data = array();
        // length of the code
        $len = strlen($code);
        switch (strtoupper($type)) {
            case 'A':  // MODE A
                $startid = 103;
                for ($i = 0; $i < $len; ++$i) {
                    $char = $code[$i];
                    $char_id = ord($char);
                    if (($char_id >= 241) and ($char_id <= 244)) {
                        $code_data[] = $fnc_a[$char_id];
                    } elseif (($char_id >= 0) and ($char_id <= 95)) {
                        $code_data[] = strpos($keys_a, $char);
                    } else {
                        throw new InvalidCharacterException('Char '.$char.' is unsupported');
                    }
                }
                break;

            case 'B':  // MODE B
                $startid = 104;
                for ($i = 0; $i < $len; ++$i) {
                    $char = $code[$i];
                    $char_id = ord($char);
                    if (($char_id >= 241) and ($char_id <= 244)) {
                        $code_data[] = $fnc_b[$char_id];
                    } elseif (($char_id >= 32) and ($char_id <= 127)) {
                        $code_data[] = strpos($keys_b, $char);
                    } else {
                        throw new InvalidCharacterException('Char '.$char.' is unsupported');
                    }
                }
                break;

            case 'C':  // MODE C
                $startid = 105;
                if (241 == ord($code[0])) {
                    $code_data[] = 102;
                    $code = substr($code, 1);
                    --$len;
                }
                if (0 != ($len % 2)) {
                    throw new InvalidLengthException('Length must be even');
                }
                for ($i = 0; $i < $len; $i += 2) {
                    $chrnum = $code[$i].$code[$i + 1];
                    if (preg_match('/([0-9]{2})/', $chrnum) > 0) {
                        $code_data[] = intval($chrnum);
                    } else {
                        throw new InvalidCharacterException();
                    }
                }
                break;

            default:  // MODE AUTO
                // split code into sequences
                $sequence = array();
                // get numeric sequences (if any)
                $numseq = array();
                preg_match_all('/([0-9]{4,})/', $code, $numseq, PREG_OFFSET_CAPTURE);
                if (isset($numseq[1]) and !empty($numseq[1])) {
                    $end_offset = 0;
                    foreach ($numseq[1] as $val) {
                        $offset = $val[1];

                        // numeric sequence
                        $slen = strlen($val[0]);
                        if (0 != ($slen % 2)) {
                            // the length must be even
                            ++$offset;
                            $val[0] = substr($val[0], 1);
                        }

                        if ($offset > $end_offset) {
                            // non numeric sequence
                            $sequence = array_merge($sequence,
                                $this->get128ABsequence(substr($code, $end_offset, ($offset - $end_offset))));
                        }
                        // numeric sequence fallback
                        $slen = strlen($val[0]);
                        if (0 != ($slen % 2)) {
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
                        case 'A':
                            if (0 == $key) {
                                $startid = 103;
                            } elseif ('A' != $sequence[($key - 1)][0]) {
                                if ((1 == $seq[2]) and ($key > 0) and ('B' == $sequence[($key - 1)][0]) and (!isset($sequence[($key - 1)][3]))) {
                                    // single character shift
                                    $code_data[] = 98;
                                    // mark shift
                                    $sequence[$key][3] = true;
                                } elseif (!isset($sequence[($key - 1)][3])) {
                                    $code_data[] = 101;
                                }
                            }
                            for ($i = 0; $i < $seq[2]; ++$i) {
                                $char = $seq[1][$i];
                                $char_id = ord($char);
                                if (($char_id >= 241) and ($char_id <= 244)) {
                                    $code_data[] = $fnc_a[$char_id];
                                } else {
                                    $code_data[] = strpos($keys_a, $char);
                                }
                            }
                            break;

                        case 'B':
                            if (0 == $key) {
                                $tmpchr = ord($seq[1][0]);
                                if ((1 == $seq[2]) and ($tmpchr >= 241) and ($tmpchr <= 244) and isset($sequence[($key + 1)]) and ('B' != $sequence[($key + 1)][0])) {
                                    switch ($sequence[($key + 1)][0]) {
                                        case 'A':
                                            $startid = 103;
                                            $sequence[$key][0] = 'A';
                                            $code_data[] = $fnc_a[$tmpchr];
                                            break;

                                        case 'C':
                                            $startid = 105;
                                            $sequence[$key][0] = 'C';
                                            $code_data[] = $fnc_a[$tmpchr];
                                            break;
                                    }
                                    break;
                                }
                                $startid = 104;
                            } elseif ('B' != $sequence[($key - 1)][0]) {
                                if ((1 == $seq[2]) and ($key > 0) and ('A' == $sequence[($key - 1)][0]) and (!isset($sequence[($key - 1)][3]))) {
                                    // single character shift
                                    $code_data[] = 98;
                                    // mark shift
                                    $sequence[$key][3] = true;
                                } elseif (!isset($sequence[($key - 1)][3])) {
                                    $code_data[] = 100;
                                }
                            }
                            for ($i = 0; $i < $seq[2]; ++$i) {
                                $char = $seq[1][$i];
                                $char_id = ord($char);
                                if (($char_id >= 241) and ($char_id <= 244)) {
                                    $code_data[] = $fnc_b[$char_id];
                                } else {
                                    $code_data[] = strpos($keys_b, $char);
                                }
                            }
                            break;

                        case 'C':
                            if (0 == $key) {
                                $startid = 105;
                            } elseif ('C' != $sequence[($key - 1)][0]) {
                                $code_data[] = 99;
                            }
                            for ($i = 0; $i < $seq[2]; $i += 2) {
                                $chrnum = $seq[1][$i].$seq[1][$i + 1];
                                $code_data[] = intval($chrnum);
                            }
                            break;
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
                if (0 == ($j % 2)) {
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
}
