<?php

namespace Picqer\Barcode\Types;

use Picqer\Barcode\Barcode;
use Picqer\Barcode\BarcodeBar;
use Picqer\Barcode\Exceptions\InvalidCharacterException;
use Picqer\Barcode\Exceptions\InvalidLengthException;
use Picqer\Barcode\Types\TypeInterface;

class TypeCodeITF14 implements TypeInterface
{
    /**
     * @throws InvalidLengthException
     * @throws InvalidCharacterException
     */
    public function getBarcodeData(string $code): Barcode
    {
        $barcode = new Barcode($code);

        $chr      = array();
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

        if (strlen($code) === 13) {
            $total = 0;

            for ($i = 0; $i <= strlen($code) - 1; $i++) {
                $temp  = intval($code . substr($i, 1));
                $total += $temp * (($i === 0 || $i % 2 === 0) ? 3 : 1);
            }

            $cs = $total % 10;
            $cs = 10 - $cs;
            if ($cs === 10) {
                $cs = 0;
            }

            $code .= (string) $cs;
        }

        if (strlen($code) > 14 || strlen($code) < 13) {
            throw new InvalidLengthException();
        }

        $k         = 0;
        $pbegin    = "1010";
        $pbeginarr = str_split($pbegin);

        foreach ($pbeginarr as $x) {
            $t = $x === '1';
            $w = 1;

            $barcode->addBar(new BarcodeBar($w, 1, $t));
            ++$k;
        }

        for ($i = 0; $i < strlen($code); $i += 2) {
            if (!isset($chr[$code[$i]]) || !isset($chr[$code[$i + 1]])) {
                throw new InvalidCharacterException();
            }

            $bars    = true;
            $pbars   = $chr[$code[$i]];
            $pspaces = $chr[$code[$i + 1]];
            $pmixed  = "";


            while (strlen($pbars) > 0) {
                $pmixed  .= $pbars[0] . $pspaces[0];
                $pbars   = substr($pbars, 1);
                $pspaces = substr($pspaces, 1);
            }

            $pmixedarr = str_split($pmixed);

            foreach ($pmixedarr as $x) {
                if ($bars) {
                    $t = true;
                } else {
                    $t = false;
                }
                $w = ($x === '1') ? '1' : '2';

                $barcode->addBar(new BarcodeBar($w, 1, $t));
                $bars = !$bars;
                ++$k;
            }
        }

        $pend    = "1101";
        $pendarr = str_split($pend);

        foreach ($pendarr as $x) {
            $t = $x == '1';
            $w = 1;

            $barcode->addBar(new BarcodeBar($w, 1, $t));
            ++$k;
        }

        return $barcode;
    }
}
