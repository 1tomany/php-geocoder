<?php

namespace OneToMany\Geocoder\Resource;

use OneToMany\Geocoder\Exception\RangeException;

use function is_numeric;
use function sprintf;
use function trim;

final readonly class Response
{
    /**
     * @var ?non-empty-string
     */
    public ?string $id;

    public const int UNKNOWN_ACCURACY = -1;
    public const int MINIMAL_ACCURACY = 5000;

    /**
     * @param int|float|numeric-string|null $latitude
     * @param int|float|numeric-string|null $longitude
     * @param int<self::UNKNOWN_ACCURACY, self::MINIMAL_ACCURACY> $accuracy
     *
     * @throws RangeException when the accuracy is not within the expected range
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
        public int $accuracy = self::UNKNOWN_ACCURACY,
    ) {
        if (null !== $id) {
            $id = trim($id);
        }

        $this->id = '' !== $id ? $id : null;

        if ($this->accuracy < self::UNKNOWN_ACCURACY || $this->accuracy > self::MINIMAL_ACCURACY) {
            throw new RangeException(sprintf('The accuracy must be in the range [%d,%d].', self::UNKNOWN_ACCURACY, self::MINIMAL_ACCURACY));
        }
    }

    public static function notFound(): static
    {
        return new static(null);
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
