<?php

namespace OneToMany\Geocoder\Bridge\Google\Response\Geocode;

final readonly class Results
{
    /**
     * @param list<Result> $results
     */
    public function __construct(
        public array $results = [],
    ) {
    }

    public function getFirstResult(): ?Result
    {
        return $this->results[0] ?? null;
    }
}
