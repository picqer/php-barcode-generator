<?php

namespace Picqer\Barcode\Types;

abstract class Type
{
    abstract public function getBarcodeData(string $code): array;
}
