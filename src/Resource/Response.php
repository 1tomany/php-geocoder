<?php

namespace OneToMany\Geocoder\Resource;

use OneToMany\Geocoder\Exception\RangeException;

use function array_slice;
use function func_get_args;
use function func_num_args;
use function hash;
use function implode;
use function is_int;
use function is_numeric;
use function is_string;
use function Symfony\Component\String\u;
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
     * @var ?non-empty-lowercase-string
     */
    public ?string $hash;

    /**
     * @var positive-int
     */
    public const int COMPONENT_COUNT = 7;

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
        if (is_string($id)) {
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

        $this->hash = static::hash($this->number, $this->street, $this->unit, $this->city, $this->zip, $this->state, $this->country);
    }

    /**
     * @return ?non-empty-lowercase-string
     */
    public static function hash(
        ?string $number = null,
        ?string $street = null,
        ?string $unit = null,
        ?string $city = null,
        ?string $zip = null,
        ?string $state = null,
        ?string $country = null,
    ): ?string {
        $argv = func_get_args();

        if (func_num_args() > self::COMPONENT_COUNT) {
            $argv = array_slice($argv, 0, self::COMPONENT_COUNT);
        }

        if ([] === $argv) {
            return null;
        }

        $bits = [];

        foreach ($argv as $bit) {
            if (!is_string($bit)) {
                continue;
            }

            $bit = u($bit)->ascii()->camel()->lower()->trim();

            if (false === $bit->isEmpty()) {
                $bits[] = $bit->toString();
            }
        }

        return [] !== $bits ? hash('sha256', implode(':', $bits)) : null;
    }

    public static function notFound(): static
    {
        return new static(id: null);
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
     * @return ?non-empty-lowercase-string
     */
    public function getHash(): ?string
    {
        return $this->hash;
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
     * @phpstan-assert-if-true non-empty-string $this->getStreet()
     */
    public function hasStreet(): bool
    {
        return null !== $this->getStreet();
    }

    /**
     * @phpstan-assert-if-true int|float|numeric-string $this->latitude
     * @phpstan-assert-if-true int|float|numeric-string $this->longitude
     * @phpstan-assert-if-true int|float|numeric-string $this->getLatitude()
     * @phpstan-assert-if-true int|float|numeric-string $this->getLongitude()
     */
    public function hasCoordinates(): bool
    {
        return is_numeric($this->latitude) && is_numeric($this->longitude);
    }

    /**
     * @phpstan-assert-if-true non-empty-string $this->street
     * @phpstan-assert-if-true int|float|numeric-string $this->latitude
     * @phpstan-assert-if-true int|float|numeric-string $this->longitude
     * @phpstan-assert-if-true int|float|numeric-string $this->getLatitude()
     * @phpstan-assert-if-true int|float|numeric-string $this->getLongitude()
     */
    public function isValid(): bool
    {
        return $this->hasStreet() && $this->hasCoordinates();
    }
}
