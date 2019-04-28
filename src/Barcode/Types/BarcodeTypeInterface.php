<?php

namespace Picqer\Barcode\Types;

interface BarcodeTypeInterface
{
    public function generate(): array;
}