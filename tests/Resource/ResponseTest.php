<?php

namespace OneToMany\Geocoder\Tests\Resource;

use OneToMany\Geocoder\Exception\RangeException;
use OneToMany\Geocoder\Resource\Response;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function random_int;

#[Group('UnitTests')]
#[Group('ResourceTests')]
final class ResponseTest extends TestCase
{
    public function testConstructorTrimsId(): void
    {
        $this->assertSame('place_123', new Response(id: '  place_123  ')->getId());
    }

    public function testConstructorAllowsAccuracyToBeNull(): void
    {
        $this->assertNull(new Response('place_123', accuracy: null)->getAccuracy());
    }

    public function testConstructorRequiresAccuracyToBeNullOrStrictlyPositive(): void
    {
        $accuracy = -1 * random_int(0, 1000);
        $this->assertLessThanOrEqual(0, $accuracy);

        $this->expectException(RangeException::class);
        $this->expectExceptionMessageIs('The accuracy must be NULL or a strictly positive integer.');

        new Response('place_123', accuracy: $accuracy);
    }

    public function testNotFoundReturnsInvalidResponse(): void
    {
        $this->assertFalse(Response::notFound()->isValid());
    }

    public function testIsValidRequiresNonEmptyId(): void
    {
        $response = new Response(id: null);

        $this->assertNull($response->getId());
        $this->assertFalse($response->isValid());
    }

    public function testIsValidRequiresNonEmptyStreet(): void
    {
        $response = new Response(id: 'resp_123', street: null);

        $this->assertNull($response->getStreet());
        $this->assertFalse($response->isValid());
    }

    public function testIsValidRequiresNonEmptyCity(): void
    {
        $response = new Response(
            id: 'resp_123',
            street: '123 Main St',
            city: null,
        );

        $this->assertNull($response->getCity());
        $this->assertFalse($response->isValid());
    }

    public function testIsValidRequiresNonEmptyState(): void
    {
        $response = new Response(
            id: 'resp_123',
            street: '123 Main St',
            city: 'Houston',
            state: null,
        );

        $this->assertNull($response->getState());
        $this->assertFalse($response->isValid());
    }

    public function testIsValidRequiresNonEmptyCountry(): void
    {
        $response = new Response(
            id: 'resp_123',
            street: '123 Main St',
            city: 'Houston',
            state: 'TX',
            country: null,
        );

        $this->assertNull($response->getCountry());
        $this->assertFalse($response->isValid());
    }

    public function testIsValidRequiresNonNullCoordinates(): void
    {
        $response = new Response(
            id: 'resp_123',
            street: '123 Main St',
            city: 'Houston',
            state: 'TX',
            country: 'US',
            latitude: null,
            longitude: null,
        );

        $this->assertNull($response->getLatitude());
        $this->assertNull($response->getLongitude());
        $this->assertFalse($response->isValid());
    }

    public function testIsValidRequiresNonNullAccuracy(): void
    {
        $response = new Response(
            id: 'resp_123',
            street: '123 Main St',
            city: 'Houston',
            state: 'TX',
            country: 'US',
            latitude: '32.8761733',
            longitude: '-97.1125216',
            accuracy: null,
        );

        $this->assertNull($response->getAccuracy());
        $this->assertFalse($response->isValid());
    }

    public function testIsValid(): void
    {
        $response = new Response(
            id: 'resp_123',
            street: '123 Main St',
            city: 'Houston',
            state: 'TX',
            country: 'US',
            latitude: '32.8761733',
            longitude: '-97.1125216',
            accuracy: 1,
        );

        $this->assertNotNull($response->getId());
        $this->assertNotNull($response->getStreet());
        $this->assertNotNull($response->getCity());
        $this->assertNotNull($response->getState());
        $this->assertNotNull($response->getCountry());
        $this->assertNotNull($response->getHash());
        $this->assertNotNull($response->getLatitude());
        $this->assertNotNull($response->getLongitude());
        $this->assertNotNull($response->getAccuracy());
        $this->assertTrue($response->isValid());
    }
}
