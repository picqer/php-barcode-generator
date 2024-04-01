<?php

namespace Picqer\Barcode\Helpers;

class StringHelpers
{
    public static function getSafeFilenameFrom(string $className): string {
        $lastPart = substr($className, strrpos($className, '\\') + 1);

        return preg_replace('/[^a-zA-Z0-9_ \-+]/s', '-', $lastPart);
    }
}
