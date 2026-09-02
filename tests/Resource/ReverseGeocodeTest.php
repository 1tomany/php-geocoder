<?php

namespace OneToMany\Geocoder\Tests\Resource;

use OneToMany\Geocoder\Exception\DomainException;
use OneToMany\Geocoder\Exception\RangeException;
use OneToMany\Geocoder\Resource\ReverseGeocode;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('ResourceTests')]
final class ReverseGeocodeTest extends TestCase
{
    public function testConstructorAcceptsNumericCoordinates(): void
    {
        $reverse = new ReverseGeocode('32.10391494', '-96.3931030');

        $this->assertSame('32.10391494', $reverse->latitude);
        $this->assertSame('-96.3931030', $reverse->longitude);
    }

    public function testConstructorRequiresNumericLatitude(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessageIs('The latitude must be a numeric value.');

        new ReverseGeocode('invalid', -96.3931030);
    }

    public function testConstructorRequiresValidLatitude(): void
    {
        $faker = \Faker\Factory::create();

        $latitude = $faker->latitude(-180, -91);
        $this->assertLessThan(-90.0, $latitude);

        $this->expectException(RangeException::class);
        $this->expectExceptionMessageIs('The latitude must be greater than or equal to -90.0 or less than or equal to 90.0.');

        new ReverseGeocode($latitude, $faker->longitude());
    }

    public function testConstructorRequiresNumericLongitude(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessageIs('The longitude must be a numeric value.');

        new ReverseGeocode(32.10391494, 'invalid');
    }
}
