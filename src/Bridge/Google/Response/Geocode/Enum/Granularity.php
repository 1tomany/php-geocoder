<?php

namespace OneToMany\Geocoder\Bridge\Google\Response\Geocode\Enum;

enum Granularity: string
{
    case Approximate = 'APPROXIMATE';
    case GeometricCenter = 'GEOMETRIC_CENTER';
    case RangeInterpolated = 'RANGE_INTERPOLATED';
    case Rooftop = 'ROOFTOP';
    case Unspecified = 'GRANULARITY_UNSPECIFIED';

    public function getAccuracy(): float
    {
        return match ($this) {
            self::Approximate => 0.3,
            self::GeometricCenter => 0.5,
            self::RangeInterpolated => 0.7,
            self::Rooftop => 0.99,
            self::Unspecified => 0.0,
        };
    }
}
