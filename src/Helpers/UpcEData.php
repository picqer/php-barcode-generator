<?php

namespace Picqer\Barcode\Helpers;

readonly class UpcEData
{
    public function __construct(
        protected string $encodedDigits,
        protected string $checkDigit,
        protected string $gtin12
    ) {
    }

    public function getEncodedDigits(): string
    {
        return $this->encodedDigits;
    }

    public function getCheckDigit(): string
    {
        return $this->checkDigit;
    }

    public function getGtin12(): string
    {
        return $this->gtin12;
    }

    public function getHumanReadable(): string
    {
        return '0' . $this->encodedDigits . $this->checkDigit;
    }
}
