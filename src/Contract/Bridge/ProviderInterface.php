<?php

namespace OneToMany\Geocoder\Contract\Bridge;

use OneToMany\Geocoder\GeocodingVendor;
use OneToMany\Geocoder\Resource\FowardGeocode;
use OneToMany\Geocoder\Resource\Response;
use OneToMany\Geocoder\Resource\Reverse;

interface ProviderInterface
{
    public static function getVendor(): GeocodingVendor;

    public function geocode(FowardGeocode $geocode): Response;

    public function reverse(Reverse $reverse): Response;
}
