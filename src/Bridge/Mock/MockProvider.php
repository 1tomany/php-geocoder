<?php

namespace OneToMany\Geocoder\Bridge\Mock;

use OneToMany\Geocoder\Contract\Bridge\ProviderInterface;
use OneToMany\Geocoder\GeocodingVendor;
use OneToMany\Geocoder\Resource\FowardGeocode;
use OneToMany\Geocoder\Resource\Response;
use OneToMany\Geocoder\Resource\Reverse;

use function array_rand;
use function bin2hex;
use function random_bytes;
use function random_int;
use function sprintf;
use function strtolower;

final readonly class MockProvider implements ProviderInterface
{
    private \Faker\Generator $faker;

    private const array GRANULARITIES = [
        'rooftop', 'nearby', 'approximate', 'unknown',
    ];

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    /**
     * @see OneToMany\Geocoder\Contract\Bridge\ProviderInterface
     */
    #[\Override]
    public static function getVendor(): GeocodingVendor
    {
        return GeocodingVendor::Mock;
    }

    /**
     * @see OneToMany\Geocoder\Contract\Bridge\ProviderInterface
     */
    #[\Override]
    public function geocode(FowardGeocode $request): Response
    {
        return new Response(
            $this->generateId('place'),
            null,
            $request->street,
            $request->unit,
            $request->city,
            $request->zip,
            $request->state,
            $request->country,
            $this->faker->latitude(),
            $this->faker->longitude(),
            $this->getGranularity(),
            random_int(1, 1000),
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
            $this->faker->buildingNumber(),
            $this->faker->streetName(),
            $this->faker->format('secondaryAddress'), // @phpstan-ignore argument.type
            $this->faker->city(),
            $this->faker->postcode(),
            $this->faker->format('stateAbbr'), // @phpstan-ignore argument.type
            $this->faker->countryCode(),
            $reverse->latitude,
            $reverse->longitude,
            $this->getGranularity(),
            random_int(1, 1000),
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

    /**
     * @return non-empty-lowercase-string
     */
    private function getGranularity(): string
    {
        return self::GRANULARITIES[array_rand(self::GRANULARITIES)];
    }
}
