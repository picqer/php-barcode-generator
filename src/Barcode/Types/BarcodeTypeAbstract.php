<?php

namespace Picqer\Barcode\Types;

abstract class BarcodeTypeAbstract
{
    /** @var string */
    protected $code;

    /**
     * Constructor.
     *
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->code = $code;
    }

    /**
     * Checksum for standard 2 of 5 barcodes.
     *
     * @param string $code code to process
     *
     * @return int checksum
     */
    protected function checksum_s25(string $code): int
    {
        $len = strlen($code);
        $sum = 0;
        for ($i = 0; $i < $len; $i += 2) {
            $sum += $code[$i];
        }
        $sum *= 3;
        for ($i = 1; $i < $len; $i += 2) {
            $sum += ($code[$i]);
        }
        $r = $sum % 10;
        if ($r > 0) {
            $r = (10 - $r);
        }

        return $r;
    }

    /**
     * Convert binary barcode sequence to TCPDF barcode array.
     *
     * @param string $seq      barcode as binary sequence
     * @param array  $bararray barcode array
     *
     * @return array barcode representation
     */
    protected function binseq_to_array(string $seq, array $bararray): array
    {
        $len = strlen($seq);
        $w = 0;
        $k = 0;
        for ($i = 0; $i < $len; ++$i) {
            ++$w;
            if (($i == ($len - 1)) or (($i < ($len - 1)) and ($seq[$i] != $seq[($i + 1)]))) {
                if ('1' == $seq[$i]) {
                    $t = true; // bar
                } else {
                    $t = false; // space
                }
                $bararray['bcode'][$k] = array('t' => $t, 'w' => $w, 'h' => 1, 'p' => 0);
                $bararray['maxw'] += $w;
                ++$k;
                $w = 0;
            }
        }

        return $bararray;
    }

    /**
     * Converts the Barcode array to a new style.
     *
     * @param array $oldBarcodeArray
     *
     * @return array
     */
    protected function convertBarcodeArrayToNewStyle(array $oldBarcodeArray): array
    {
        if (!isset($oldBarcodeArray['maxWidth'])) {
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
                $newBar['drawSpacing'] = !$oldbar['t'];

                $newBarcodeArray['bars'][] = $newBar;
            }

            return $newBarcodeArray;
        }

        return $oldBarcodeArray;
    }
}
