<?php

namespace Picqer\Barcode\Types;

interface TypeInterface
{
    public function getBarcodeData(string $code): array;
}
