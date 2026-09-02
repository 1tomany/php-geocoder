<?php

namespace OneToMany\Geocoder;

use OneToMany\Geocoder\Contract\Bridge\ProviderInterface;
use OneToMany\Geocoder\Contract\GeocodingClientInterface;
use OneToMany\Geocoder\Resource\ForwardGeocode;
use OneToMany\Geocoder\Resource\Registry;
use OneToMany\Geocoder\Resource\Response;
use OneToMany\Geocoder\Resource\ReverseGeocode;

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
    public function forward(
        string|GeocodingVendor $vendor,
        ForwardGeocode $request,
    ): Response {
        return $this->providers->get(GeocodingVendor::create($vendor))->forward($request);
    }

    /**
     * @see OneToMany\Geocoder\Contract\GeocodingClientInterface
     */
    #[\Override]
    public function reverse(
        string|GeocodingVendor $vendor,
        ReverseGeocode $request,
    ): Response {
        return $this->providers->get(GeocodingVendor::create($vendor))->reverse($request);
    }

    /**
     * @see OneToMany\Geocoder\Contract\GeocodingClientInterface
     */
    #[\Override]
    public function request(
        string|GeocodingVendor $vendor,
        ForwardGeocode|ReverseGeocode $request,
    ): Response {
        if ($request instanceof ForwardGeocode) {
            return $this->forward($vendor, $request);
        }

        return $this->reverse($vendor, $request);
    }
}
