<?php

namespace Picqer\Barcode\Types;

class PostnetPlanet extends BarcodeTypeAbstract implements BarcodeTypeInterface
{
    /** @var bool */
    protected $isPlanet;

    public function __construct(string $code, bool $isPlanet = false)
    {
        parent::__construct($code);
        $this->isPlanet = $isPlanet;
    }

    /**
     * Generate the PostnetPlanet data.
     *
     * @return array
     */
    public function generate(): array
    {
        return $this->convertBarcodeArrayToNewStyle($this->barcode_postnet($this->code, $this->isPlanet));
    }

    /**
     * POSTNET and PLANET barcodes.
     * Used by U.S. Postal Service for automated mail sorting.
     *
     * @param string $code   zip code to represent. Must be a string containing a zip code of the form DDDDD orDDDDD-DDDD.
     * @param bool   $planet if true print the PLANET barcode, otherwise print POSTNET
     *
     * @return array barcode representation
     */
    protected function barcode_postnet(string $code, bool $planet = false): array
    {
        // bar length
        if ($planet) {
            $barlen = array(
                0 => array(1, 1, 2, 2, 2),
                1 => array(2, 2, 2, 1, 1),
                2 => array(2, 2, 1, 2, 1),
                3 => array(2, 2, 1, 1, 2),
                4 => array(2, 1, 2, 2, 1),
                5 => array(2, 1, 2, 1, 2),
                6 => array(2, 1, 1, 2, 2),
                7 => array(1, 2, 2, 2, 1),
                8 => array(1, 2, 2, 1, 2),
                9 => array(1, 2, 1, 2, 2),
            );
        } else {
            $barlen = array(
                0 => array(2, 2, 1, 1, 1),
                1 => array(1, 1, 1, 2, 2),
                2 => array(1, 1, 2, 1, 2),
                3 => array(1, 1, 2, 2, 1),
                4 => array(1, 2, 1, 1, 2),
                5 => array(1, 2, 1, 2, 1),
                6 => array(1, 2, 2, 1, 1),
                7 => array(2, 1, 1, 1, 2),
                8 => array(2, 1, 1, 2, 1),
                9 => array(2, 1, 2, 1, 1),
            );
        }
        $bararray = array('code' => $code, 'maxw' => 0, 'maxh' => 2, 'bcode' => array());
        $k = 0;
        $code = str_replace('-', '', $code);
        $code = str_replace(' ', '', $code);
        $len = strlen($code);
        // calculate checksum
        $sum = 0;
        for ($i = 0; $i < $len; ++$i) {
            $sum += intval($code[$i]);
        }
        $chkd = ($sum % 10);
        if ($chkd > 0) {
            $chkd = (10 - $chkd);
        }
        $code .= $chkd;
        $len = strlen($code);
        // start bar
        $bararray['bcode'][$k++] = array('t' => 1, 'w' => 1, 'h' => 2, 'p' => 0);
        $bararray['bcode'][$k++] = array('t' => 0, 'w' => 1, 'h' => 2, 'p' => 0);
        $bararray['maxw'] += 2;
        for ($i = 0; $i < $len; ++$i) {
            for ($j = 0; $j < 5; ++$j) {
                $h = $barlen[$code[$i]][$j];
                $p = floor(1 / $h);
                $bararray['bcode'][$k++] = array('t' => 1, 'w' => 1, 'h' => $h, 'p' => $p);
                $bararray['bcode'][$k++] = array('t' => 0, 'w' => 1, 'h' => 2, 'p' => 0);
                $bararray['maxw'] += 2;
            }
        }
        // end bar
        $bararray['bcode'][$k++] = array('t' => 1, 'w' => 1, 'h' => 2, 'p' => 0);
        ++$bararray['maxw'];

        return $bararray;
    }
}
