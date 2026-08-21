<?php

namespace OneToMany\Geocoder;

use OneToMany\Geocoder\Exception\InvalidArgumentException;

use function sprintf;

enum Vendor: string
{
    case Google = 'google';
    case Mock = 'mock';

    /**
     * @throws InvalidArgumentException when the vendor is not valid
     */
    public static function create(string|self $vendor): self
    {
        if (!$vendor instanceof self) {
            try {
                return self::from($vendor);
            } catch (\ValueError $e) {
                throw new InvalidArgumentException(sprintf('The vendor "%s" is not valid.', $vendor), previous: $e);
            }
        }

        return $vendor;
    }

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return non-empty-lowercase-string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @phpstan-assert-if-true self::Google $this
     */
    public function isGoogle(): bool
    {
        return self::Google === $this;
    }

    /**
     * @phpstan-assert-if-true self::Mock $this
     */
    public function isMock(): bool
    {
        return self::Mock === $this;
    }
}
