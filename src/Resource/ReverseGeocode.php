<?php

namespace OneToMany\Geocoder\Resource;

use OneToMany\Geocoder\Exception\DomainException;
use OneToMany\Geocoder\Exception\RangeException;

use function is_numeric;

final readonly class ReverseGeocode
{
    public float $latitude;
    public float $longitude;

    /**
     * @throws DomainException when the latitude is not a numeric value
     * @throws RangeException when the latitude is less than -90.0 or greater than 90.0
     * @throws DomainException when the longitude is not a numeric value
     * @throws RangeException when the longitude is less than -180.0 or greater than 180.0
     */
    public function __construct(
        int|float|string $latitude,
        int|float|string $longitude,
    ) {
        if (!is_numeric($latitude)) {
            throw new DomainException('The latitude must be a numeric value.');
        }

        $latitude = (float) $latitude;

        if ($latitude < -90.0 || $latitude > 90.0) {
            throw new RangeException('The latitude must be greater than or equal to -90.0 or less than or equal to 90.0.');
        }

        $this->latitude = $latitude;

        if (!is_numeric($longitude)) {
            throw new DomainException('The longitude must be a numeric value.');
        }

        $longitude = (float) $longitude;

        if ($longitude < -180.0 || $longitude > 180.0) {
            throw new RangeException('The longitude must be greater than or equal to -180.0 or less than or equal to 180.0.');
        }

        $this->longitude = $longitude;
    }
}
