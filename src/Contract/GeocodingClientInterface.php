<?php

namespace OneToMany\Geocoder\Contract;

use OneToMany\Geocoder\GeocodingVendor;
use OneToMany\Geocoder\Resource\Geocode;
use OneToMany\Geocoder\Resource\Response;
use OneToMany\Geocoder\Resource\Reverse;

interface GeocodingClientInterface
{
    public function geocode(string|GeocodingVendor $vendor, Geocode $geocode): Response;

    public function reverse(string|GeocodingVendor $vendor, Reverse $reverse): Response;
}
