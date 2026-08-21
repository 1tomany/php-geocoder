<?php

namespace OneToMany\Geocoder\Contract\Bridge;

use OneToMany\Geocoder\Resource\Geocode;
use OneToMany\Geocoder\Resource\Response;
use OneToMany\Geocoder\Resource\Reverse;
use OneToMany\Geocoder\Vendor;

interface ProviderInterface
{
    public static function getVendor(): Vendor;

    public function geocode(Geocode $geocode): Response;

    public function reverse(Reverse $reverse): Response;
}
