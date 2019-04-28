<?php

namespace Picqer\Barcode\Types;

class S25 extends BarcodeTypeAbstract implements BarcodeTypeInterface
{
    /** @var bool */
    protected $hasChecksum;

    public function __construct(string $code, bool $hasChecksum)
    {
        parent::__construct($code);
        $this->hasChecksum = $hasChecksum;
    }

    /**
     * Generate the S25 data.
     *
     * @return array
     */
    public function generate(): array
    {
        return $this->convertBarcodeArrayToNewStyle($this->barcode_s25($this->code, $this->hasChecksum));
    }

    /**
     * Standard 2 of 5 barcodes.
     * Used in airline ticket marking, photofinishing
     * Contains digits (0 to 9) and encodes the data only in the width of bars.
     *
     * @param string $code     code to represent
     * @param int    $checksum if true add a checksum to the code
     *
     * @return array barcode representation
     */
    protected function barcode_s25(string $code, bool $checksum = false): array
    {
        $chr['0'] = '10101110111010';
        $chr['1'] = '11101010101110';
        $chr['2'] = '10111010101110';
        $chr['3'] = '11101110101010';
        $chr['4'] = '10101110101110';
        $chr['5'] = '11101011101010';
        $chr['6'] = '10111011101010';
        $chr['7'] = '10101011101110';
        $chr['8'] = '10101110111010';
        $chr['9'] = '10111010111010';
        if ($checksum) {
            // add checksum
            $code .= $this->checksum_s25($code);
        }
        if (0 != (strlen($code) % 2)) {
            // add leading zero if code-length is odd
            $code = '0'.$code;
        }
        $seq = '11011010';
        $clen = strlen($code);
        for ($i = 0; $i < $clen; ++$i) {
            $digit = $code[$i];
            if (!isset($chr[$digit])) {
                throw new InvalidCharacterException('Char '.$digit.' is unsupported');
            }
            $seq .= $chr[$digit];
        }
        $seq .= '1101011';
        $bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());

        return $this->binseq_to_array($seq, $bararray);
    }
}
