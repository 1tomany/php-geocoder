<?php

namespace OneToMany\Geocoder\Tests\Bridge\Google;

use OneToMany\Geocoder\Bridge\Google\GoogleProvider;
use OneToMany\Geocoder\Bridge\Transport;
use OneToMany\Geocoder\Exception\DomainException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\Serializer;

final class GoogleProviderTest extends TestCase
{
    public function testConstructorRequiresNonEmptyApiKey(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessageIs('The Google API key cannot be empty.');

        new GoogleProvider($this->createTransport(), apiKey: '  ');
    }

    private function createTransport(): Transport
    {
        return new Transport(HttpClient::create(), new Serializer());
    }
}
