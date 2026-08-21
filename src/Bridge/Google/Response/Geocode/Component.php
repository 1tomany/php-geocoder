<?php

namespace OneToMany\Geocoder\Bridge\Google\Response\Geocode;

use function in_array;
use function trim;

final readonly class Component
{
    /**
     * @var ?non-empty-string
     */
    public ?string $text;

    /**
     * @param list<non-empty-string> $types
     */
    public function __construct(
        public ?string $longText = null,
        public ?string $shortText = null,
        public array $types = [],
        public ?string $languageCode = null,
    ) {
        if ('' === $text = trim((string) $this->shortText)) {
            $text = trim((string) $this->longText);
        }

        $this->text = '' !== $text ? $text : null;
    }

    public function hasType(string $type): bool
    {
        return in_array($type, $this->types, true);
    }
}
