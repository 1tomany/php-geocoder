<?php

namespace OneToMany\Geocoder\Contract\Bridge;

use OneToMany\Geocoder\GeocodingVendor;
use OneToMany\Geocoder\Resource\Geocode;
use OneToMany\Geocoder\Resource\Response;
use OneToMany\Geocoder\Resource\Reverse;

interface ProviderInterface
{
    public static function getVendor(): GeocodingVendor;

    public function geocode(Geocode $geocode): Response;

    public function reverse(Reverse $reverse): Response;
}
