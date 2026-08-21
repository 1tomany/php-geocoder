<?php

namespace OneToMany\Geocoder\Contract;

use OneToMany\Geocoder\Resource\Geocode;
use OneToMany\Geocoder\Resource\Response;
use OneToMany\Geocoder\Resource\Reverse;
use OneToMany\Geocoder\Vendor;

interface GeocoderClientInterface
{
    public function geocode(string|Vendor $vendor, Geocode $geocode): Response;

    public function reverse(string|Vendor $vendor, Reverse $reverse): Response;
}
