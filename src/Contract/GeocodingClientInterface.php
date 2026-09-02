<?php

namespace OneToMany\Geocoder\Contract;

use OneToMany\Geocoder\GeocodingVendor;
use OneToMany\Geocoder\Resource\FowardGeocode;
use OneToMany\Geocoder\Resource\Response;
use OneToMany\Geocoder\Resource\ReverseGeocode;

interface GeocodingClientInterface
{
    public function forward(string|GeocodingVendor $vendor, FowardGeocode $request): Response;

    public function reverse(string|GeocodingVendor $vendor, ReverseGeocode $request): Response;
}
