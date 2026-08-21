<?php

namespace OneToMany\Geocoder\Tests\Resource;

use OneToMany\Geocoder\Bridge\Mock\MockProvider;
use OneToMany\Geocoder\Contract\Bridge\ProviderInterface;
use OneToMany\Geocoder\Exception\InvalidArgumentException;
use OneToMany\Geocoder\Resource\Geocode;
use OneToMany\Geocoder\Resource\Registry;
use OneToMany\Geocoder\Resource\Response;
use OneToMany\Geocoder\Resource\Reverse;
use OneToMany\Geocoder\Vendor;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('ResourceTests')]
final class RegistryTest extends TestCase
{
    public function testGetReturnsRegisteredProvider(): void
    {
        $mockProvider = new MockProvider();

        $registry = new Registry([
            $mockProvider,
        ]);

        $this->assertSame($mockProvider, $registry->get(Vendor::Mock));
    }

    public function testConstructorRequiresUniqueProvider(): void
    {
        $mockProvider = new MockProvider();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageIs('The "'.$mockProvider->getVendor()->getValue().'" provider is already registered.');

        new Registry([$mockProvider, $mockProvider]);
    }

    public function testGetRequiresRegisteredProvider(): void
    {
        $mockProvider = new MockProvider();

        $registry = new Registry([
            $mockProvider,
        ]);

        $vendor = Vendor::Google;
        $this->assertNotSame($vendor, $mockProvider->getVendor());

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageIs('The "'.$vendor->getValue().'" provider is not registered.');

        $registry->get($vendor);
    }
}
