<?php

namespace OneToMany\Geocoder\Bridge\Google\Response\Geocode;

use OneToMany\Geocoder\Bridge\Google\Response\Geocode\Enum\Granularity;
use OneToMany\Geocoder\Resource\Response;

final readonly class Result
{
    /**
     * @param non-empty-string $placeId
     * @param list<Component> $addressComponents
     */
    public function __construct(
        public string $placeId,
        public ?Location $location,
        public Granularity $granularity,
        public array $addressComponents = [],
    ) {
    }

    public function toResponse(): Response
    {
        return new Response(
            $this->placeId,
            $this->findComponent('street_number'),
            $this->findComponent('route'),
            $this->findComponent('subpremise'),
            $this->findComponent('locality', 'postal_town'),
            $this->findComponent('postal_code'),
            $this->findComponent('administrative_area_level_1'),
            $this->findComponent('country'),
            $this->location?->getLatitude(),
            $this->location?->getLongitude(),
            $this->granularity->getValue(),
            $this->granularity->getAccuracy(),
        );
    }

    /**
     * @return ?non-empty-string
     */
    private function findComponent(string ...$componentTypes): ?string
    {
        foreach ($this->addressComponents as $component) {
            foreach ($componentTypes as $type) {
                if ($component->hasType($type)) {
                    return $component->text;
                }
            }
        }

        return null;
    }
}
