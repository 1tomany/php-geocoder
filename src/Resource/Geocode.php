<?php

namespace OneToMany\Geocoder\Resource;

use function implode;

readonly class Geocode
{
    /**
     * @var non-empty-string
     */
    public string $line;

    /**
     * @param non-empty-string $street
     */
    public function __construct(
        public string $street,
        public ?string $unit,
        public ?string $city,
        public ?string $zip,
        public ?string $state,
        public ?string $country,
    ) {
        $this->line = implode(' ', [$this->street, $this->unit]);
    }
}
