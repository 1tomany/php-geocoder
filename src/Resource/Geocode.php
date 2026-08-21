<?php

namespace OneToMany\Geocoder\Resource;

use OneToMany\Geocoder\Exception\InvalidArgumentException;

use function trim;

final class Geocode
{
    /**
     * @var non-empty-string
     */
    public readonly string $street;

    /**
     * @throws InvalidArgumentException when the street is empty
     */
    public function __construct(
        ?string $street,
        public readonly ?string $unit,
        public readonly ?string $city,
        public readonly ?string $zip,
        public readonly ?string $state,
        public readonly ?string $country,
    ) {
        if ('' === $street = trim((string) $street)) {
            throw new InvalidArgumentException('The street cannot be empty.');
        }

        $this->street = $street;
    }

    public string $line {
        get => $this->createLine();
    }

    private function createLine(): string
    {
        return trim("{$this->street} {$this->unit}");
    }
}
