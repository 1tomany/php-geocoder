<?php

namespace OneToMany\Geocoder\Resource;

use OneToMany\Geocoder\Exception\InvalidArgumentException;

use function is_string;
use function trim;
use function vsprintf;

final class Geocode
{
    /**
     * @var ?non-empty-string
     */
    public readonly ?string $number;

    /**
     * @var ?non-empty-string
     */
    public readonly ?string $street;

    /**
     * @throws InvalidArgumentException when the number and street are empty
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

        $number = '' !== $number ? $number : null;

        if (is_string($street)) {
            $street = trim($street);
        }

        $street = '' !== $street ? $street : null;

        if (null === $number && null === $street) {
            throw new InvalidArgumentException('Both the number and street cannot be empty.');
        }

        $this->number = $number;
        $this->street = $street;
    }

    public string $line {
        get => $this->createLine();
    }

    private function createLine(): string
    {
        $line = vsprintf('%s %s %s', [
            trim((string) $this->number),
            trim((string) $this->street),
            trim((string) $this->unit),
        ]);

        return trim($line);
    }
}
