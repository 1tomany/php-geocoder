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

        new Geocode($street, null, null, null, null, null);
    }

    /**
     * @return non-empty-list<array{?string}>
     */
    public static function providerEmptyStreet(): array
    {
        return [
            [null],
            [''],
            ['   '],
        ];
    }

    public function testConstructorTrimsStreet(): void
    {
        $geocode = new Geocode('  123 Main Street  ', null, null, null, null, null);

        $this->assertSame('123 Main Street', $geocode->street);
    }

    public function testLineCombinesStreetAndUnit(): void
    {
        $geocode = new Geocode('123 Main Street', 'Suite 100', null, null, null, null);

        $this->assertSame('123 Main Street Suite 100', $geocode->line);
    }

    public function testLineDoesNotIncludeTrailingWhitespaceWithoutUnit(): void
    {
        $geocode = new Geocode('123 Main Street', null, null, null, null, null);

        $this->assertSame('123 Main Street', $geocode->line);
    }
}
