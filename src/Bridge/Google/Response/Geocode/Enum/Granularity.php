<?php

namespace OneToMany\Geocoder\Bridge\Google\Response\Geocode\Enum;

use OneToMany\Geocoder\Resource\Response;

enum Granularity: string
{
    case Rooftop = 'ROOFTOP';
    case RangeInterpolated = 'RANGE_INTERPOLATED';
    case GeometricCenter = 'GEOMETRIC_CENTER';
    case Approximate = 'APPROXIMATE';
    case GranularityUnspecified = 'GRANULARITY_UNSPECIFIED';

    /**
     * @return int<Response::UNKNOWN_ACCURACY, 1000>
     */
    public function getAccuracy(): int
    {
        return match ($this) {
            self::Rooftop => 1,
            self::RangeInterpolated => 25,
            self::GeometricCenter => 250,
            self::Approximate => 1000,
            default => Response::UNKNOWN_ACCURACY,
        };
    }
}
