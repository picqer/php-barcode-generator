<?php

namespace Picqer\Barcode\Types;

use Picqer\Barcode\Barcode;
use Picqer\Barcode\BarcodeBar;
use Picqer\Barcode\Exceptions\InvalidCharacterException;
use Picqer\Barcode\Exceptions\InvalidLengthException;

class TypeITF14 implements TypeInterface
{
    /**
     * @throws InvalidLengthException
     * @throws InvalidCharacterException
     */
    public function getBarcodeData(string $code): Barcode
    {
        $chr = [];
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

        if (strlen($code) === 13) {
            $code .= $this->getChecksum($code);
        }

        if (strlen($code) > 14 || strlen($code) < 13) {
            throw new InvalidLengthException();
        }

        $barcode = new Barcode($code);

        // Add start and stop codes
        $code = 'AA' . strtolower($code) . 'ZA';

        for ($charIndex = 0; $charIndex < strlen($code); $charIndex += 2) {
            if (! isset($chr[$code[$charIndex]]) || ! isset($chr[$code[$charIndex + 1]])) {
                throw new InvalidCharacterException();
            }

            $bars = true;
            $pbars = $chr[$code[$charIndex]];
            $pspaces = $chr[$code[$charIndex + 1]];
            $pmixed = '';

            while (strlen($pbars) > 0) {
                $pmixed .= $pbars[0] . $pspaces[0];
                $pbars = substr($pbars, 1);
                $pspaces = substr($pspaces, 1);
            }

            $pmixedarr = str_split($pmixed);

            foreach ($pmixedarr as $x) {
                if ($bars) {
                    $t = true;
                } else {
                    $t = false;
                }
                $width = ($x === '1') ? '1' : '2';

                $barcode->addBar(new BarcodeBar($width, 1, $t));
                $bars = ! $bars;
            }
        }

        return $barcode;
    }

    private function getChecksum(string $code): string
    {
        $total = 0;

        for ($charIndex = 0; $charIndex <= (strlen($code) - 1); $charIndex++) {
            $integerOfChar = intval($code . substr($charIndex, 1));
            $total += $integerOfChar * (($charIndex === 0 || $charIndex % 2 === 0) ? 3 : 1);
        }

        $checksum = 10 - ($total % 10);
        if ($checksum === 10) {
            $checksum = 0;
        }

        return (string)$checksum;
    }
}
