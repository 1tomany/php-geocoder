<?php

namespace OneToMany\Geocoder\Tests\Resource;

use OneToMany\Geocoder\Exception\DomainException;
use OneToMany\Geocoder\Exception\RangeException;
use OneToMany\Geocoder\Resource\ReverseGeocode;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function bcdiv;

#[Group('UnitTests')]
#[Group('ResourceTests')]
final class ReverseGeocodeTest extends TestCase
{
    public function testConstructorAcceptsNumericCoordinates(): void
    {
        $faker = \Faker\Factory::create();

        $latitude = $faker->latitude(min: -90, max: 90);
        $latitude = bcdiv((string) $latitude, '1.0', 7);

        $longitude = $faker->longitude(min: -180, max: 180);
        $longitude = bcdiv((string) $longitude, '1.0', 7);

        $reverseGeocode = new ReverseGeocode($latitude, $longitude);

        $this->assertSame($latitude, $reverseGeocode->latitude);
        $this->assertSame($longitude, $reverseGeocode->longitude);
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
        $this->expectExceptionMessageIs('The latitude must be greater than or equal to -90 or less than or equal to 90.');

        new ReverseGeocode($latitude, $faker->longitude());
    }

    public function testConstructorRequiresNumericLongitude(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessageIs('The longitude must be a numeric value.');

        new ReverseGeocode(32.10391494, 'invalid');
    }

    public function testConstructorRequiresValidLongitude(): void
    {
        $faker = \Faker\Factory::create();

        $longitude = $faker->longitude(-360, -181);
        $this->assertLessThan(-180.0, $longitude);

        $this->expectException(RangeException::class);
        $this->expectExceptionMessageIs('The longitude must be greater than or equal to -180 or less than or equal to 180.');

        new ReverseGeocode($faker->latitude(), $longitude);
    }
}
