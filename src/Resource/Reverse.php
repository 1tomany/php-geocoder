<?php

namespace OneToMany\Geocoder\Resource;

use OneToMany\Geocoder\Exception\InvalidArgumentException;

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
     * @throws InvalidArgumentException when the latitude is not a numeric value
     * @throws InvalidArgumentException when the longitude is not a numeric value
     */
    public function __construct(
        int|float|string $latitude,
        int|float|string $longitude,
    ) {
        if (!is_numeric($latitude)) {
            throw new InvalidArgumentException('The latitude must be a numeric value.');
        }

        if (!is_numeric($longitude)) {
            throw new InvalidArgumentException('The longitude must be a numeric value.');
        }

        $this->latitude = $latitude;
        $this->longitude = $longitude;
    }
}
