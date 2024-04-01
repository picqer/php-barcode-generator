<?php
/**
 * Adapted by Darren Stephens <darren.stephens@durham.ac.uk>
 * from Java implementation of Telepen by <rstuart114@gmail.com> Robin Stuart 
 * at https://github.com/woo-j/OkapiBarcode which uses the 
 * Apache License 2.0 http://www.apache.org/licenses/LICENSE-2.0
 *
 * Implements Telepen (also known as Telepen Alpha), and Telepen Numeric.
 *
 * Telepen can encode ASCII text input and includes a modulo-127 check digit.
 * Telepen Numeric allows compression of numeric data into a Telepen symbol. Data
 * can consist of pairs of numbers or pairs consisting of a numerical digit followed
 * by an X character. Telepen Numeric also includes a mod-127 check digit.
 */

namespace Picqer\Barcode\Types;

class TypeTelepenNumeric extends TypeTelepen
{
    public function __construct()
    {
        parent::__construct('numeric');
    }
}
