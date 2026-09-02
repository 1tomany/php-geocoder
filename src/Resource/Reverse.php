<?php

namespace OneToMany\Geocoder\Resource;

use OneToMany\Geocoder\Exception\DomainException;

use function is_numeric;

final readonly class Reverse
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
     * @throws DomainException when the longitude is not a numeric value
     */
    public function __construct(
        int|float|string $latitude,
        int|float|string $longitude,
    ) {
        if (!is_numeric($latitude)) {
            throw new DomainException('The latitude must be a numeric value.');
        }

        $this->latitude = $latitude;

        if (!is_numeric($longitude)) {
            throw new DomainException('The longitude must be a numeric value.');
        }

        $this->longitude = $longitude;
    }
}
