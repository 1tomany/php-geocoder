<?php

namespace OneToMany\Geocoder\Contract\Bridge;

use OneToMany\Geocoder\GeocodingVendor;
use OneToMany\Geocoder\Resource\ForwardGeocode;
use OneToMany\Geocoder\Resource\Response;
use OneToMany\Geocoder\Resource\ReverseGeocode;

interface ProviderInterface
{
    public static function getVendor(): GeocodingVendor;

    public function forward(ForwardGeocode $request): Response;

    public function reverse(ReverseGeocode $request): Response;
}
