<?php

namespace OneToMany\Geocoder\Resource;

use OneToMany\Geocoder\Exception\DomainException;
use OneToMany\Geocoder\Exception\RangeException;

use function is_numeric;

final readonly class ReverseGeocode
{
    /**
     * @var int|float|numeric-string
     */
    public int|float|string $latitude;

    /**
     * @var int|float|numeric-string
     */
    public int|float|string $longitude;

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

        $this->latitude = $latitude;

        if (\floor($this->latitude) < -90 || \ceil($latitude) > 90) {
            throw new RangeException('The latitude must be greater than or equal to -90 or less than or equal to 90.');
        }

        if (!is_numeric($longitude)) {
            throw new DomainException('The longitude must be a numeric value.');
        }

        if ((float) $longitude < -180.0 || (float) $longitude > 180.0) {
            throw new RangeException('The longitude must be greater than or equal to -180 or less than or equal to 180.');
        }

        $this->longitude = $longitude;
    }
}
