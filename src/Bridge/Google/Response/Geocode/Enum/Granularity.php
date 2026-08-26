<?php

namespace OneToMany\Geocoder\Bridge\Google\Response\Geocode\Enum;

enum Granularity: string
{
    case Rooftop = 'ROOFTOP';
    case RangeInterpolated = 'RANGE_INTERPOLATED';
    case GeometricCenter = 'GEOMETRIC_CENTER';
    case Approximate = 'APPROXIMATE';
    case GranularityUnspecified = 'GRANULARITY_UNSPECIFIED';

    /**
     * @return non-empty-string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return ?positive-int
     */
    public function getAccuracy(): ?int
    {
        return match ($this) {
            self::Rooftop => 1,
            self::RangeInterpolated => 25,
            self::GeometricCenter => 250,
            self::Approximate => 1000,
            default => null,
        };
    }
}
