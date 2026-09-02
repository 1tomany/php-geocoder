<?php

namespace OneToMany\Geocoder\Tests;

use OneToMany\Geocoder\Bridge\Mock\MockProvider;
use OneToMany\Geocoder\Exception\DomainException;
use OneToMany\Geocoder\GeocodingVendor;
use OneToMany\Geocoder\ProviderRegistry;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
final class ProviderRegistryTest extends TestCase
{
    public function testGetReturnsRegisteredProvider(): void
    {
        $mockProvider = new MockProvider();

        $registry = new ProviderRegistry([
            $mockProvider,
        ]);

        $this->assertSame($mockProvider, $registry->get(GeocodingVendor::Mock));
    }

    public function testConstructorRequiresUniqueProvider(): void
    {
        $mockProvider = new MockProvider();

        $this->expectException(DomainException::class);
        $this->expectExceptionMessageIs('The "'.$mockProvider->getVendor()->getValue().'" provider is already registered.');

        new ProviderRegistry([$mockProvider, $mockProvider]);
    }

    public function testGetRequiresRegisteredProvider(): void
    {
        $mockProvider = new MockProvider();

        $registry = new ProviderRegistry([
            $mockProvider,
        ]);

        $vendor = GeocodingVendor::Google;
        $this->assertNotSame($vendor, $mockProvider->getVendor());

        $this->expectException(DomainException::class);
        $this->expectExceptionMessageIs('The "'.$vendor->getValue().'" provider is not registered.');

        $registry->get($vendor);
    }
}
