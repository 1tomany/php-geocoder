<?php

namespace OneToMany\Geocoder\Tests\Resource;

use OneToMany\Geocoder\Exception\DomainException;
use OneToMany\Geocoder\Resource\ForwardGeocode;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('ResourceTests')]
final class ForwardGeocodeTest extends TestCase
{
    #[DataProvider('providerEmptyNumberAndStreet')]
    public function testConstructorRequiresNonEmptyNumberAndStreet(
        ?string $number,
        ?string $street,
    ): void {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessageIs('Both the number and street cannot be empty.');

        new ForwardGeocode($number, $street);
    }

    /**
     * @return non-empty-list<array{?string,?string}>
     */
    public static function providerEmptyNumberAndStreet(): array
    {
        $provider = [
            ['', ''],
            [null, null],
            [' ', null],
            [' ', '   '],
            [null, '  '],
            ['  ', '  '],
        ];

        return $provider;
    }

    public function testConstructorTrimsStreet(): void
    {
        $this->assertSame('Main Street', new ForwardGeocode('123  ', '   Main Street  ')->street);
    }

    #[DataProvider('providerNumberStreetAndLine')]
    public function testConstructorCombinesNumberAndStreet(
        int|string|null $number,
        ?string $street,
        string $line,
    ): void {
        $this->assertSame($line, new ForwardGeocode($number, $street)->line);
    }

    /**
     * @return non-empty-list<array{int|string|null,?string,non-empty-string}>
     */
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
        $this->assertSame('123 Main Street Suite 100', new ForwardGeocode(null, '123 Main Street', 'Suite 100')->line);
    }

    public function testLineDoesNotIncludeTrailingWhitespaceWithoutUnit(): void
    {
        $this->assertSame('123 Main Street', new ForwardGeocode('123', 'Main Street   ')->line);
    }
}
