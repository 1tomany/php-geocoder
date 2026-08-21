<?php

namespace OneToMany\Geocoder\Tests\Resource;

use OneToMany\Geocoder\Resource\Response;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('UnitTests')]
#[Group('ResourceTests')]
final class ResponseTest extends TestCase
{
    public function testConstructorTrimsId(): void
    {
        $response = new Response('  place_123  ');

        $this->assertSame('place_123', $response->id);
    }

    public function testConstructorNullifiesEmptyId(): void
    {
        $response = new Response('   ');

        $this->assertNull($response->id);
    }

    public function testNotFoundReturnsMissingResponse(): void
    {
        $response = Response::notFound();

        $this->assertNull($response->id);
        $this->assertSame(-1.0, $response->accuracy);
        $this->assertFalse($response->hasStreet());
        $this->assertFalse($response->hasCoordinates());
        $this->assertFalse($response->isFound());
    }

    public function testIsFoundRequiresStreetAndCoordinates(): void
    {
        $response = new Response(
            'place_123',
            street: '123 Main Street',
            latitude: '32.10391494',
            longitude: '-96.3931030',
        );

        $this->assertTrue($response->hasStreet());
        $this->assertTrue($response->hasCoordinates());
        $this->assertTrue($response->isFound());
    }

    public function testIsFoundRequiresStreet(): void
    {
        $response = new Response('place_123', latitude: 32.10391494, longitude: -96.3931030);

        $this->assertFalse($response->hasStreet());
        $this->assertTrue($response->hasCoordinates());
        $this->assertFalse($response->isFound());
    }

    public function testIsFoundRequiresCoordinates(): void
    {
        $response = new Response('place_123', street: '123 Main Street');

        $this->assertTrue($response->hasStreet());
        $this->assertFalse($response->hasCoordinates());
        $this->assertFalse($response->isFound());
    }
}
