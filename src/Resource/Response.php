<?php

namespace OneToMany\Geocoder\Resource;

use OneToMany\Geocoder\Exception\RangeException;

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
     * @param ?positive-int $accuracy
     *
     * @throws RangeException when the accuracy is not-null and not strictly positive
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
        public ?int $accuracy = null,
    ) {
        if (null !== $id) {
            $id = trim($id);
        }

        $this->id = '' !== $id ? $id : null;

        if (null !== $this->accuracy && $this->accuracy < 1) {
            throw new RangeException('The accuracy must be NULL or a strictly positive integer.');
        }
    }

    public static function notFound(): static
    {
        return new static(null);
    }

    /**
     * @return ?non-empty-string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return ?non-empty-string
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * @return ?non-empty-string
     */
    public function getUnit(): ?string
    {
        return $this->unit;
    }

    /**
     * @return ?non-empty-string
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @return ?non-empty-string
     */
    public function getZip(): ?string
    {
        return $this->zip;
    }

    /**
     * @return ?non-empty-string
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * @return ?non-empty-string
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @return ?positive-int
     */
    public function getAccuracy(): ?int
    {
        return $this->accuracy;
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
