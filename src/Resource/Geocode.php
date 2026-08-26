<?php

namespace OneToMany\Geocoder\Resource;

use OneToMany\Geocoder\Exception\InvalidArgumentException;

use function is_string;
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
        int|string|null $number,
        ?string $street,
        public readonly ?string $unit = null,
        public readonly ?string $city = null,
        public readonly ?string $zip = null,
        public readonly ?string $state = null,
        public readonly ?string $country = null,
    ) {
        if (is_int($number) || is_string($number)) {
            $number = trim((string) $number);
        }

        if (is_string($street)) {
            $street = trim($street);
        }

        $street = "{$number} {$street}";

        if ('' === $street = trim($street)) {
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
