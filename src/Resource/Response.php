<?php

namespace OneToMany\Geocoder\Resource;

use function is_numeric;
use function trim;

final readonly class Response
{
    /**
     * @param ?non-empty-string $id
     * @param int|float|numeric-string|null $latitude
     * @param int|float|numeric-string|null $longitude
     */
    public function __construct(
        public ?string $id,
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
    }

    public static function createInvalid(): static
    {
        return new static(null, accuracy: 0.0);
    }

    /**
     * @phpstan-assert-if-true non-empty-string $this->street
     */
    public function isValid(): bool
    {
        return
            '' !== trim((string) $this->street)
            && true === is_numeric($this->latitude)
            && true === is_numeric($this->longitude)
        ;
    }
}
