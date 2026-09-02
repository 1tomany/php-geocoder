<?php

namespace OneToMany\Geocoder\Tests\Validator;

use OneToMany\Geocoder\GeocodingVendor;
use OneToMany\Geocoder\Validator\GeocodingVendorName;
use OneToMany\Geocoder\Validator\GeocodingVendorNameValidator;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

#[Group('ValidatorTests')]
final class GeocodingVendorNameValidatorTest extends TestCase
{
    public function testValidateRequiresGeocodingVendorNameConstraint(): void
    {
        $this->expectException(UnexpectedTypeException::class);
        $this->expectExceptionMessageIs('Expected argument of type "'.GeocodingVendorName::class.'", "'.Assert\Blank::class.'" given');

        new GeocodingVendorNameValidator()->validate('google', new Assert\Blank());
    }

    public function testValidateIgnoresNullValues(): void
    {
        $this->expectNotToPerformAssertions();

        new GeocodingVendorNameValidator()->validate(null, new GeocodingVendorName());
    }

    public function testValidateRequiresValueToBeString(): void
    {
        $this->expectException(UnexpectedValueException::class);
        $this->expectExceptionMessageIs('Expected argument of type "string", "array" given');

        new GeocodingVendorNameValidator()->validate(['google'], new GeocodingVendorName());
    }

    public function testValidatingValidGeocodingVendor(): void
    {
        $this->expectNotToPerformAssertions();

        $vendor = GeocodingVendor::cases()[
            \array_rand(GeocodingVendor::cases())
        ];

        new GeocodingVendorNameValidator()->validate($vendor->value, new GeocodingVendorName());
    }
}
