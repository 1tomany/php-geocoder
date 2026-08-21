<?php

namespace OneToMany\Geocoder\Bridge\Google\Response\Geocode;

final readonly class Location
{
    /**
     * @param int|float|numeric-string $latitude
     * @param int|float|numeric-string $longitude
     */
    public function __construct(
        public int|float|string $latitude = 0.0,
        public int|float|string $longitude = 0.0,
    ) {
    }
}
