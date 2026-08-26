<?php

namespace OneToMany\Geocoder\Resource;

use OneToMany\Geocoder\Exception\RangeException;

use function is_int;
use function is_numeric;
use function is_string;
use function trim;

final readonly class Response
{
    /**
     * @var ?non-empty-string
     */
    public ?string $id;

    /**
     * @var ?non-empty-string
     */
    public ?string $number;

    /**
     * @var ?non-empty-string
     */
    public ?string $street;

    /**
     * @var ?non-empty-string
     */
    public ?string $unit;

    /**
     * @var ?non-empty-string
     */
    public ?string $city;

    /**
     * @var ?non-empty-string
     */
    public ?string $zip;

    /**
     * @var ?non-empty-string
     */
    public ?string $state;

    /**
     * @var ?non-empty-string
     */
    public ?string $country;

    /**
     * @param int|float|numeric-string|null $latitude
     * @param int|float|numeric-string|null $longitude
     *
     * @throws RangeException when the accuracy is not-null and not strictly positive
     */
    public function __construct(
        ?string $id,
        int|string|null $number = null,
        ?string $street = null,
        ?string $unit = null,
        ?string $city = null,
        ?string $zip = null,
        ?string $state = null,
        ?string $country = null,
        public int|float|string|null $latitude = null,
        public int|float|string|null $longitude = null,
        public ?string $granularity = null,
        public ?int $accuracy = null,
    ) {
        if (null !== $id) {
            $id = trim($id);
        }

        $this->id = '' !== $id ? $id : null;

        if (is_int($number) || is_string($number)) {
            $number = trim((string) $number);
        }

        $this->number = '' !== $number ? $number : null;

        if (is_string($street)) {
            $street = trim($street);
        }

        $this->street = '' !== $street ? $street : null;

        if (is_string($unit)) {
            $unit = trim($unit);
        }

        $this->unit = '' !== $unit ? $unit : null;

        if (is_string($city)) {
            $city = trim($city);
        }

        $this->city = '' !== $city ? $city : null;

        if (is_string($zip)) {
            $zip = trim($zip);
        }

        $this->zip = '' !== $zip ? $zip : null;

        if (is_string($state)) {
            $state = trim($state);
        }

        $this->state = '' !== $state ? $state : null;

        if (is_string($country)) {
            $country = trim($country);
        }

        $this->country = '' !== $country ? $country : null;

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
    public function getNumber(): ?string
    {
        return $this->number;
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
     * @return int|float|numeric-string|null
     */
    public function getLatitude(): int|float|string|null
    {
        return $this->latitude;
    }

    /**
     * @return int|float|numeric-string|null
     */
    public function getLongitude(): int|float|string|null
    {
        return $this->longitude;
    }

    public function getGranularity(): ?string
    {
        return $this->granularity;
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
