<?php

namespace OneToMany\Geocoder;

use OneToMany\Geocoder\Contract\Bridge\ProviderInterface;
use OneToMany\Geocoder\Contract\GeocodingClientInterface;
use OneToMany\Geocoder\Resource\FowardGeocode;
use OneToMany\Geocoder\Resource\Registry;
use OneToMany\Geocoder\Resource\Response;
use OneToMany\Geocoder\Resource\Reverse;

final readonly class GeocodingClient implements GeocodingClientInterface
{
    /**
     * @var Registry<ProviderInterface>
     */
    private Registry $providers;

    /**
     * @param iterable<ProviderInterface> $providers
     */
    public function __construct(iterable $providers)
    {
        $this->providers = new Registry($providers);
    }

    /**
     * @see OneToMany\Geocoder\Contract\GeocodingClientInterface
     */
    #[\Override]
    public function forward(string|GeocodingVendor $vendor, FowardGeocode $request): Response
    {
        return $this->providers->get(GeocodingVendor::create($vendor))->geocode($request);
    }

    /**
     * @see OneToMany\Geocoder\Contract\GeocodingClientInterface
     */
    #[\Override]
    public function reverse(string|GeocodingVendor $vendor, Reverse $reverse): Response
    {
        return $this->providers->get(GeocodingVendor::create($vendor))->reverse($reverse);
    }
}
