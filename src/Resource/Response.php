<?php

namespace OneToMany\Geocoder\Resource;

use function is_numeric;
use function trim;

final readonly class Response
{
    /**
     * @var ?non-empty-string
     */
    public ?string $id;

    /**
     * @param int|float|numeric-string|null $latitude
     * @param int|float|numeric-string|null $longitude
     */
    public function __construct(
        ?string $id,
        public ?string $street = null,
        public ?string $unit = null,
        public ?string $city = null,
        public ?string $zip = null,
        public ?string $state = null,
        public ?string $country = null,
        public int|float|string|null $latitude = null,
        public int|float|string|null $longitude = null,
        public int|float $accuracy = 0.0,
    ) {
        if (null !== $id) {
            $id = trim($id);
        }

        $this->id = '' !== $id ? $id : null;
    }

    public static function notFound(): static
    {
        return new static(null, accuracy: -1.0);
    }

    /**
     * @phpstan-assert-if-true non-empty-string $this->street
     */
    public function hasStreet(): bool
    {
        return null !== $this->street ? ('' !== trim($this->street)) : false;
    }

    /**
     * @phpstan-assert-if-true int|float|numeric-string $this->latitude
     * @phpstan-assert-if-true int|float|numeric-string $this->longitude
     */
    public function hasCoordinates(): bool
    {
        return is_numeric($this->latitude) && is_numeric($this->longitude);
    }

    /**
     * @phpstan-assert-if-true non-empty-string $this->street
     * @phpstan-assert-if-true int|float|numeric-string $this->latitude
     * @phpstan-assert-if-true int|float|numeric-string $this->longitude
     */
    public function isFound(): bool
    {
        return $this->hasStreet() && $this->hasCoordinates();
    }
}
