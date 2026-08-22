<?php

namespace OneToMany\Geocoder\Bridge\Google\Response\Geocode\Enum;

enum Granularity: string
{
    case Rooftop = 'ROOFTOP';
    case RangeInterpolated = 'RANGE_INTERPOLATED';
    case GeometricCenter = 'GEOMETRIC_CENTER';
    case Approximate = 'APPROXIMATE';
    case GranularityUnspecified = 'GRANULARITY_UNSPECIFIED';

    public function getAccuracy(): float
    {
        return match ($this) {
            self::Rooftop => 0.99, // 1
            self::RangeInterpolated => 0.7, // 25
            self::GeometricCenter => 0.5, // 250
            self::Approximate => 0.3, // 1000
            self::GranularityUnspecified => 0.0, // -1
        };
    }
}
