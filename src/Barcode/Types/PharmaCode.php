<?php

namespace Picqer\Barcode\Types;

class PharmaCode extends BarcodeTypeAbstract implements BarcodeTypeInterface
{
    /**
     * Generate the PharmaCode data.
     *
     * @return array
     */
    public function generate(): array
    {
        return $this->convertBarcodeArrayToNewStyle($this->barcode_pharmacode($this->code));
    }

    /**
     * Pharmacode
     * Contains digits (0 to 9).
     *
     * @param string $code code to represent
     *
     * @return array barcode representation
     */
    protected function barcode_pharmacode(string $code): array
    {
        $seq = '';
        $code = intval($code);
        while ($code > 0) {
            if (0 == ($code % 2)) {
                $seq .= '11100';
                $code -= 2;
            } else {
                $seq .= '100';
                --$code;
            }
            $code /= 2;
        }
        $seq = substr($seq, 0, -2);
        $seq = strrev($seq);
        $bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 1, 'bcode' => array());

        return $this->binseq_to_array($seq, $bararray);
    }
}
