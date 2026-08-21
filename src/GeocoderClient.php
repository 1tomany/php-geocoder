<?php

namespace OneToMany\Geocoder;

use OneToMany\Geocoder\Contract\Bridge\ProviderInterface;
use OneToMany\Geocoder\Contract\GeocoderClientInterface;
use OneToMany\Geocoder\Resource\Geocode;
use OneToMany\Geocoder\Resource\Registry;
use OneToMany\Geocoder\Resource\Response;
use OneToMany\Geocoder\Resource\Reverse;

final readonly class GeocoderClient implements GeocoderClientInterface
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
     * @see OneToMany\Geocoder\Contract\GeocoderClientInterface
     */
    #[\Override]
    public function geocode(string|Vendor $vendor, Geocode $geocode): Response
    {
        return $this->providers->get(Vendor::create($vendor))->geocode($geocode);
    }

    /**
     * @see OneToMany\Geocoder\Contract\GeocoderClientInterface
     */
    #[\Override]
    public function reverse(string|Vendor $vendor, Reverse $reverse): Response
    {
        return $this->providers->get(Vendor::create($vendor))->reverse($reverse);
    }
}
