<?php

namespace OneToMany\Geocoder\Tests;

use OneToMany\Geocoder\Exception\InvalidArgumentException;
use OneToMany\Geocoder\Vendor;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function array_map;

#[Group('UnitTests')]
final class VendorTest extends TestCase
{
    #[DataProvider('providerVendor')]
    public function testCreateReturnsSelf(Vendor $vendor): void
    {
        $this->assertSame($vendor, Vendor::create($vendor));
    }

    #[DataProvider('providerVendor')]
    public function testCreateReturnsVendorFromValue(Vendor $vendor): void
    {
        $this->assertSame($vendor, Vendor::create($vendor->getValue()));
    }

    /**
     * @return non-empty-list<array{Vendor}>
     */
    public static function providerVendor(): array
    {
        return array_map(static fn (Vendor $vendor): array => [$vendor], Vendor::cases());
    }

    public function testCreateRequiresValidVendor(): void
    {
        $vendor = 'invalid_vendor';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageIs('The vendor "'.$vendor.'" is not valid.');

        Vendor::create($vendor);
    }

    #[DataProvider('providerNameAndValue')]
    public function testGettingNameAndValue(
        Vendor $vendor,
        string $name,
        string $value,
    ): void {
        $this->assertSame($name, $vendor->getName());
        $this->assertSame($value, $vendor->getValue());
    }

    /**
     * @return non-empty-list<array{Vendor, non-empty-string, non-empty-lowercase-string}>
     */
    public static function providerNameAndValue(): array
    {
        $mapper = static function (Vendor $vendor): array {
            return [$vendor, $vendor->getName(), $vendor->getValue()];
        };

        return array_map($mapper, Vendor::cases());
    }

    public function testIsGoogle(): void
    {
        $this->assertTrue(Vendor::Google->isGoogle()); // @phpstan-ignore method.alreadyNarrowedType
    }

    public function testIsMock(): void
    {
        $this->assertTrue(Vendor::Mock->isMock()); // @phpstan-ignore method.alreadyNarrowedType
    }
}
