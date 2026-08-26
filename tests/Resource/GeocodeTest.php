<?php

namespace OneToMany\Geocoder\Tests\Resource;

use OneToMany\Geocoder\Exception\InvalidArgumentException;
use OneToMany\Geocoder\Resource\Geocode;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('ResourceTests')]
final class GeocodeTest extends TestCase
{
    #[DataProvider('providerEmptyStreet')]
    public function testConstructorRequiresNonEmptyStreet(?string $street): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageIs('The street cannot be empty.');

        new Geocode(null, $street, null, null, null, null, null);
    }

    /**
     * @return non-empty-list<array{?string}>
     */
    public static function providerEmptyStreet(): array
    {
        $provider = [
            [null],
            [''],
            ['   '],
        ];

        return $provider;
    }

    public function testConstructorTrimsStreet(): void
    {
        $this->assertSame('123 Main Street', new Geocode('123  ', '   Main Street  ')->street);
    }

    #[DataProvider('providerNumberStreetAndLine')]
    public function testConstructorCombinesNumberAndStreet(
        int|string|null $number,
        ?string $street,
        string $line,
    ): void {
        $this->assertSame($line, new Geocode($number, $street)->line);
    }

    public static function providerNumberStreetAndLine(): array
    {
        $provider = [
            [null, '8 Plover', '8 Plover'],
            ['', 'Rusk St', 'Rusk St'],
            [' ', '19 Berry Way', '19 Berry Way'],
            [8, 'Main St', '8 Main St'],
            [0, 'Wall St', '0 Wall St'],
            ['18 Merry Lane', null, '18 Merry Lane'],
        ];

        return $provider;
    }

    public function testLineCombinesStreetAndUnit(): void
    {
        $this->assertSame('123 Main Street Suite 100', new Geocode(null, '123 Main Street', 'Suite 100')->line);
    }

    public function testLineDoesNotIncludeTrailingWhitespaceWithoutUnit(): void
    {
        $this->assertSame('123 Main Street', new Geocode('123', 'Main Street   ')->line);
    }
}
