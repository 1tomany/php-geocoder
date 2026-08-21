<?php

namespace OneToMany\Geocoder\Bridge\Mock;

use OneToMany\Geocoder\Contract\Bridge\ProviderInterface;
use OneToMany\Geocoder\Resource\Geocode;
use OneToMany\Geocoder\Resource\Response;
use OneToMany\Geocoder\Resource\Reverse;
use OneToMany\Geocoder\Vendor;

use function bin2hex;
use function random_bytes;
use function sprintf;
use function strtolower;

final readonly class MockProvider implements ProviderInterface
{
    private \Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    /**
     * @see OneToMany\Geocoder\Contract\Bridge\ProviderInterface
     */
    #[\Override]
    public static function getVendor(): Vendor
    {
        return Vendor::Mock;
    }

    /**
     * @see OneToMany\Geocoder\Contract\Bridge\ProviderInterface
     */
    #[\Override]
    public function geocode(Geocode $geocode): Response
    {
        return new Response(
            $this->generateId('place'),
            $geocode->street,
            $geocode->unit,
            $geocode->city,
            $geocode->zip,
            $geocode->state,
            $geocode->country,
            $this->faker->latitude(),
            $this->faker->longitude(),
            $this->faker->randomFloat(2, 0, 1),
        );
    }

    /**
     * @see OneToMany\Geocoder\Contract\Bridge\ProviderInterface
     */
    #[\Override]
    public function reverse(Reverse $reverse): Response
    {
        return new Response(
            $this->generateId('place'),
            $this->faker->streetAddress(),
            $this->faker->streetSuffix(),
            $this->faker->city(),
            $this->faker->postcode(),
            $this->faker->countryCode(),
            $this->faker->countryCode(),
            $reverse->latitude,
            $reverse->longitude,
            $this->faker->randomFloat(2, 0, 1),
        );
    }

    /**
     * @param non-empty-string $prefix
     * @param positive-int $suffixLength
     *
     * @return non-empty-lowercase-string
     */
    private function generateId(string $prefix, int $suffixLength = 4): string
    {
        return strtolower(sprintf('%s_%s', $prefix, bin2hex(random_bytes($suffixLength))));
    }
}
