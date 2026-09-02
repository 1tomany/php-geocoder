<?php

namespace OneToMany\Geocoder\Tests\Resource;

use OneToMany\Geocoder\Exception\DomainException;
use OneToMany\Geocoder\Exception\RangeException;
use OneToMany\Geocoder\Resource\ReverseGeocode;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function sprintf;

#[Group('UnitTests')]
#[Group('ResourceTests')]
final class ReverseGeocodeTest extends TestCase
{
    public function testConstructorAcceptsNumericCoordinates(): void
    {
        $faker = \Faker\Factory::create();

        $latitude = $faker->latitude(-90, 90);
        $latitude = sprintf('%.7f', $latitude);

        $longitude = $faker->longitude(-180, 180);
        $longitude = sprintf('%.7f', $longitude);

        $reverse = new ReverseGeocode($latitude, $longitude);

        $this->assertSame($latitude, $reverse->latitude);
        $this->assertSame($longitude, $reverse->longitude);
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
