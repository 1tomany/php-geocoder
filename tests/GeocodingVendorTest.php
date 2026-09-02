<?php

namespace OneToMany\Geocoder\Tests;

use OneToMany\Geocoder\Exception\DomainException;
use OneToMany\Geocoder\GeocodingVendor;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function array_map;

#[Group('UnitTests')]
final class GeocodingVendorTest extends TestCase
{
    public function testCreateTrimsAndLowercasesVendor(): void
    {
        $vendor = ' GOOGLE ';
        $this->assertNull(GeocodingVendor::tryFrom($vendor)); // @phpstan-ignore method.alreadyNarrowedType

        $geocodingVendor = GeocodingVendor::Google;
        $this->assertSame($geocodingVendor, GeocodingVendor::create($vendor));
    }

    #[DataProvider('providerGeocodingVendor')]
    public function testCreateReturnsSelf(GeocodingVendor $vendor): void
    {
        $this->assertSame($vendor, GeocodingVendor::create($vendor));
    }

    #[DataProvider('providerGeocodingVendor')]
    public function testCreateReturnsVendorFromValue(GeocodingVendor $vendor): void
    {
        $this->assertSame($vendor, GeocodingVendor::create($vendor->getValue()));
    }

    /**
     * @return non-empty-list<
     *   array{
     *     0: GeocodingVendor,
     *   },
     * >
     */
    public static function providerGeocodingVendor(): array
    {
        return array_map(static fn (GeocodingVendor $vendor): array => [$vendor], GeocodingVendor::cases());
    }

    public function testCreateRequiresValidVendor(): void
    {
        $vendor = 'invalid_vendor';

        $this->expectException(DomainException::class);
        $this->expectExceptionMessageIs('The geocoding vendor "'.$vendor.'" is not valid.');

        GeocodingVendor::create($vendor);
    }

    #[DataProvider('providerNameAndValue')]
    public function testGettingNameAndValue(
        GeocodingVendor $vendor,
        string $name,
        string $value,
    ): void {
        $this->assertSame($name, $vendor->getName());
        $this->assertSame($value, $vendor->getValue());
    }

    /**
     * @return non-empty-list<
     *   array{
     *     0: GeocodingVendor,
     *     1: non-empty-string,
     *     2: non-empty-lowercase-string,
     *   },
     * >
     */
    public static function providerNameAndValue(): array
    {
        $mapper = static function (GeocodingVendor $vendor): array {
            return [$vendor, $vendor->getName(), $vendor->getValue()];
        };

        return array_map($mapper, GeocodingVendor::cases());
    }

    public function testIsGoogle(): void
    {
        $this->assertTrue(GeocodingVendor::Google->isGoogle()); // @phpstan-ignore method.alreadyNarrowedType
    }

    public function testIsMock(): void
    {
        $this->assertTrue(GeocodingVendor::Mock->isMock()); // @phpstan-ignore method.alreadyNarrowedType
    }
}
