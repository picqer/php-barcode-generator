<?php

use PHPUnit\Framework\TestCase;
use Picqer\Barcode\Barcode;
use Picqer\Barcode\Exceptions\InvalidCharacterException;
use Picqer\Barcode\Exceptions\InvalidCheckDigitException;
use Picqer\Barcode\Exceptions\InvalidFormatException;
use Picqer\Barcode\Exceptions\InvalidLengthException;
use Picqer\Barcode\Helpers\UpcEConverter;
use Picqer\Barcode\Types\TypeUpcE;

class TypeUpcETest extends TestCase
{
    /**
     * Vectors from GS1 General Specifications 24.0, figures 5.2.2.4.1-2 through 5.2.2.4.1-5.
     *
     * @dataProvider officialGs1Vectors
     */
    public function test_official_gs1_vectors(string $gtin12, string $upcE, string $expectedModules)
    {
        $this->assertSame($upcE, UpcEConverter::compress($gtin12));
        $this->assertSame($gtin12, UpcEConverter::expand($upcE));

        $inputs = [
            $upcE,
            substr($upcE, 0, 7),
            $gtin12,
            substr($gtin12, 0, 11),
        ];

        foreach ($inputs as $input) {
            $barcode = (new TypeUpcE())->getBarcode($input);

            $this->assertSame($upcE, $barcode->getBarcode());
            $this->assertSame(51, $barcode->getWidth());
            $this->assertSame($expectedModules, $this->getModuleSequence($barcode));
        }
    }

    public function test_accepts_the_upc_e_value_reported_in_issue_119()
    {
        $this->assertSame('012000001345', UpcEConverter::expand('01213405'));
        $this->assertSame('01213405', (new TypeUpcE())->getBarcode('01213405')->getBarcode());
        $this->assertSame('01213405', (new TypeUpcE())->getBarcode('0121340')->getBarcode());
    }

    /**
     * @dataProvider allCheckDigitParityVectors
     */
    public function test_all_check_digit_parity_patterns(string $upcE, string $expectedModules)
    {
        $barcode = (new TypeUpcE())->getBarcode($upcE);

        $this->assertSame(51, $barcode->getWidth());
        $this->assertSame($expectedModules, $this->getModuleSequence($barcode));
    }

    /**
     * @dataProvider invalidLengths
     */
    public function test_rejects_invalid_lengths(string $code)
    {
        $this->expectException(InvalidLengthException::class);

        (new TypeUpcE())->getBarcode($code);
    }

    /**
     * @dataProvider invalidCharacters
     */
    public function test_rejects_non_numeric_input(string $code)
    {
        $this->expectException(InvalidCharacterException::class);

        (new TypeUpcE())->getBarcode($code);
    }

    public function test_rejects_non_zero_number_system()
    {
        $this->expectException(InvalidFormatException::class);

        (new TypeUpcE())->getBarcode('1123455');
    }

    /**
     * @dataProvider invalidZeroSuppressionValues
     */
    public function test_rejects_invalid_zero_suppression_values(string $code)
    {
        $this->expectException(InvalidFormatException::class);

        (new TypeUpcE())->getBarcode($code);
    }

    public function test_rejects_upc_a_value_that_cannot_be_zero_suppressed()
    {
        $this->expectException(InvalidFormatException::class);

        (new TypeUpcE())->getBarcode('01234567890');
    }

    /**
     * @dataProvider invalidCheckDigits
     */
    public function test_rejects_invalid_check_digits(string $code)
    {
        $this->expectException(InvalidCheckDigitException::class);

        (new TypeUpcE())->getBarcode($code);
    }

    public static function officialGs1Vectors(): array
    {
        return [
            ['012345000058', '01234558', '101011001100100110100001010001101100010111001010101'],
            ['045670000080', '04567840', '101001110101110010000101011101101101110100011010101'],
            ['034000005673', '03456703', '101010000100111010110001010111101110110100111010101'],
            ['098400000751', '09847531', '101001011100010010100011001000101100010111101010101'],
        ];
    }

    public static function allCheckDigitParityVectors(): array
    {
        return [
            ['00000000', '101010011101001110100111000110100011010001101010101'],
            ['00000161', '101010011101001110001101010011100110010101111010101'],
            ['00000192', '101010011101001110001101000110101100110001011010101'],
            ['00000213', '101010011101001110001101000110100100110110011010101'],
            ['00000154', '101010011100011010100111010011100110010110001010101'],
            ['00000125', '101010011100011010001101010011101100110010011010101'],
            ['00000116', '101010011100011010001101000110101100110110011010101'],
            ['00000107', '101010011100011010100111000110101100110001101010101'],
            ['00000028', '101010011100011010100111000110100011010011011010101'],
            ['00000019', '101010011100011010001101010011100011010110011010101'],
        ];
    }

    public static function invalidLengths(): array
    {
        return [
            [''],
            ['123456'],
            ['123456789'],
            ['1234567890'],
            ['1234567890123'],
        ];
    }

    public static function invalidCharacters(): array
    {
        return [
            ['01234A5'],
            ['01234 5'],
            ['01234.5'],
        ];
    }

    public static function invalidZeroSuppressionValues(): array
    {
        return [
            ['0120343'],
            ['0123004'],
            ['0123405'],
        ];
    }

    public static function invalidCheckDigits(): array
    {
        return [
            ['01234559'],
            ['012345000059'],
        ];
    }

    protected function getModuleSequence(Barcode $barcode): string
    {
        $sequence = '';

        foreach ($barcode->getBars() as $bar) {
            $sequence .= str_repeat($bar->isBar() ? '1' : '0', $bar->getWidth());
        }

        return $sequence;
    }
}
