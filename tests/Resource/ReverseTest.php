<?php

namespace OneToMany\Geocoder\Tests\Resource;

use OneToMany\Geocoder\Exception\DomainException;
use OneToMany\Geocoder\Resource\Reverse;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('ResourceTests')]
final class ReverseTest extends TestCase
{
    public function testConstructorAcceptsNumericCoordinates(): void
    {
        $reverse = new Reverse('32.10391494', '-96.3931030');

        $this->assertSame('32.10391494', $reverse->latitude);
        $this->assertSame('-96.3931030', $reverse->longitude);
    }

    public function testConstructorRequiresNumericLatitude(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessageIs('The latitude must be a numeric value.');

        new Reverse('invalid', -96.3931030);
    }

    public function testConstructorRequiresNumericLongitude(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessageIs('The longitude must be a numeric value.');

        new Reverse(32.10391494, 'invalid');
    }
}
