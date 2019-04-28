<?php

namespace Picqer\Barcode\Types;

use Picqer\Barcode\Exceptions\InvalidCharacterException;

class I25 extends BarcodeTypeAbstract implements BarcodeTypeInterface
{
    /** @var bool */
    protected $hasChecksum;

    public function __construct(string $code, bool $hasChecksum)
    {
        parent::__construct($code);
        $this->hasChecksum = $hasChecksum;
    }

    /**
     * Generate the I25 data.
     *
     * @return array
     */
    public function generate(): array
    {
        return $this->convertBarcodeArrayToNewStyle($this->barcode_i25($this->code, $this->hasChecksum));
    }

    /**
     * Interleaved 2 of 5 barcodes.
     * Compact numeric code, widely used in industry, air cargo
     * Contains digits (0 to 9) and encodes the data in the width of both bars and spaces.
     *
     * @param string $code     code to represent
     * @param bool   $checksum if true add a checksum to the code
     *
     * @return array barcode representation
     */
    protected function barcode_i25(string $code, bool $checksum = false)
    {
        $chr['0'] = '11221';
        $chr['1'] = '21112';
        $chr['2'] = '12112';
        $chr['3'] = '22111';
        $chr['4'] = '11212';
        $chr['5'] = '21211';
        $chr['6'] = '12211';
        $chr['7'] = '11122';
        $chr['8'] = '21121';
        $chr['9'] = '12121';
        $chr['A'] = '11';
        $chr['Z'] = '21';
        if ($checksum) {
            // add checksum
            $code .= $this->checksum_s25($code);
        }
        if (0 != (strlen($code) % 2)) {
            // add leading zero if code-length is odd
            $code = '0'.$code;
        }
        // add start and stop codes
        $code = 'AA'.strtolower($code).'ZA';

        $bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());
        $k = 0;
        $clen = strlen($code);
        for ($i = 0; $i < $clen; $i = ($i + 2)) {
            $char_bar = $code[$i];
            $char_space = $code[$i + 1];
            if (!isset($chr[$char_bar]) || !isset($chr[$char_space])) {
                throw new InvalidCharacterException();
            }
            // create a bar-space sequence
            $seq = '';
            $chrlen = strlen($chr[$char_bar]);
            for ($s = 0; $s < $chrlen; ++$s) {
                $seq .= $chr[$char_bar][$s].$chr[$char_space][$s];
            }
            $seqlen = strlen($seq);
            for ($j = 0; $j < $seqlen; ++$j) {
                if (0 == ($j % 2)) {
                    $t = true; // bar
                } else {
                    $t = false; // space
                }
                $w = $seq[$j];
                $bararray['bcode'][$k] = array('t' => $t, 'w' => $w, 'h' => 1, 'p' => 0);
                $bararray['maxw'] += $w;
                ++$k;
            }
        }

        return $bararray;
    }
}
