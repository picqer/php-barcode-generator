<?php

/**
 * General PHP Barcode Generator
 *
 * @author Casper Bakker - picqer.com
 * Based on TCPDF Barcode Generator
 */

// Copyright (C) 2002-2015 Nicola Asuni - Tecnick.com LTD
//
// This file is part of TCPDF software library.
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

use Picqer\Barcode\Exceptions\BarcodeException;
use Picqer\Barcode\Exceptions\InvalidCharacterException;
use Picqer\Barcode\Exceptions\InvalidCheckDigitException;
use Picqer\Barcode\Exceptions\InvalidFormatException;
use Picqer\Barcode\Exceptions\InvalidLengthException;
use Picqer\Barcode\Exceptions\UnknownTypeException;

abstract class BarCodeGeneratorInternatioanal extends BarcodeGenerator
{

    /**
     * Do some optimisation for a variable character type string
     *
     * @param string $code
     * @param int $len
     * @return array
     */
    protected function optimiseSequence($code, $len)
    {
        // split code into sequences
        $sequence = array();

        // SPECIAL CASE: We had a set of barcodes that started with a % sign
        // followed by an odd number of characters.  While the generated barcode
        // was valid it wasn't considered optimal on the grounds that there were
        // two character encoding switches in the resulting barcode where it was
        // possible to produce the same representation with only a single
        // switch if we encoded the first two characters as another encoding and
        // all subsequent characters as type C.  This hack explicitly sets up
        // that sequence.
        if (preg_match("/^%\d{27,27}$/", $code)) {
            $sequence = array_merge(
                $sequence,
                $this->get128ABsequence(substr($code, 0, 2)),
                [['C', substr($code, 2), 26]]
            );
            return $sequence;
        }

        /**
         * DPD International: If Dubai, UAE, Candaian, British, Neterland postcodes contains a mixed string & numbers,
         * then the bar code should be generated on a different way.
         *
         * Example:
         * %POBOX7215501576001732302784
         *
         * First 8 character should be "B"
         *
         * BBBBBBBCCCCCCCCCCCCCCCCCCC
         *
         */

        //$code = '%POBOX7215501576001734302784';
        if ( !is_integer(substr(ltrim($code,'%'), 0, 7))) {
            $sequence = array_merge(
                $sequence,
                $this->get128ABsequence(substr($code, 0, 8)),
                [['C', substr($code, 8), strlen(substr($code, 8))]]
            );
            //var_dump($sequence);

            return $sequence;
        }


        // get numeric sequences (if any)
        $numseq = array();
        preg_match_all('/([0-9]{4,})/', $code, $numseq, PREG_OFFSET_CAPTURE);
        if (isset($numseq[1]) AND ! empty($numseq[1])) {
            $end_offset = 0;
            foreach ($numseq[1] as $val) {
                $offset = $val[1];
                if ($offset > $end_offset) {
                    // non numeric sequence
                    $sequence = array_merge($sequence, $this->get128ABsequence(substr($code, $end_offset, ($offset - $end_offset))));
                }
                // numeric sequence
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

        return $sequence;
    }

}
