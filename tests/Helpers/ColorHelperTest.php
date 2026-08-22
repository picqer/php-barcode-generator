<?php

namespace Helpers;

use Picqer\Barcode\Exceptions\UnknownColorException;
use Picqer\Barcode\Helpers\ColorHelper;
use PHPUnit\Framework\TestCase;

class ColorHelperTest extends TestCase
{
    public function test_convert_color_black()
    {
        $result = ColorHelper::getArrayFromColorString('black');

        $this->assertEquals([0, 0, 0], $result);
    }

    public function test_convert_color_hex_345()
    {
        $result = ColorHelper::getArrayFromColorString('#345');

        $this->assertEquals([51, 68, 85], $result);
    }

    public function test_convert_color_hex_334455()
    {
        $result = ColorHelper::getArrayFromColorString('#334455');

        $this->assertEquals([51, 68, 85], $result);
    }

    public function test_convert_color_hex_632343()
    {
        $result = ColorHelper::getArrayFromColorString('#632343');

        $this->assertEquals([99, 35, 67], $result);
    }

    public function test_throws_exception_on_unknown_color()
    {
        $this->expectException(UnknownColorException::class);

        ColorHelper::getArrayFromColorString('aubergine');
    }

    public function test_throws_exception_on_invalid_short_hex_color()
    {
        $this->expectException(UnknownColorException::class);

        ColorHelper::getArrayFromColorString('#ggg');
    }

    public function test_throws_exception_on_invalid_long_hex_color()
    {
        $this->expectException(UnknownColorException::class);

        ColorHelper::getArrayFromColorString('#12x456');
    }
}
