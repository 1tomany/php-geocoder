<?php

namespace OneToMany\Geocoder\Bridge\Google;

use OneToMany\Geocoder\Bridge\Google\Response\Geocode\Results;
use OneToMany\Geocoder\Bridge\Transport;
use OneToMany\Geocoder\Contract\Bridge\ProviderInterface;
use OneToMany\Geocoder\Exception\InvalidArgumentException;
use OneToMany\Geocoder\Resource\Geocode;
use OneToMany\Geocoder\Resource\Response;
use OneToMany\Geocoder\Resource\Reverse;
use OneToMany\Geocoder\Vendor;

use function array_filter;
use function sprintf;

final readonly class GoogleProvider implements ProviderInterface
{
    public const string BASE_URL = 'https://geocode.googleapis.com';

    /**
     * @param non-empty-string $apiVersion
     *
     * @throws InvalidArgumentException when the API key is empty
     */
    public function __construct(
        private Transport $transport,
        #[\SensitiveParameter] private string $apiKey,
        private string $apiVersion = 'v4',
    ) {
        if ('' === $this->apiKey) {
            throw new InvalidArgumentException(sprintf('The %s API key cannot be empty.', self::getVendor()->getName()));
        }
    }

    /**
     * @see OneToMany\Geocoder\Contract\Bridge\ProviderInterface
     */
    #[\Override]
    public static function getVendor(): Vendor
    {
        return Vendor::Google;
    }

    /**
     * @see OneToMany\Geocoder\Contract\Bridge\ProviderInterface
     */
    #[\Override]
    public function geocode(Geocode $geocode): Response
    {
        $url = $this->url('geocode', 'address');

        $query = array_filter([
            'address.addressLines' => $geocode->line,
            'address.locality' => $geocode->city,
            'address.postalCode' => $geocode->zip,
            'address.administrativeArea' => $geocode->state,
            'address.regionCode' => $geocode->country,
        ]);

        try {
            $response = $this->transport->getRequest($url, [
                'headers' => [
                    'x-goog-api-key' => $this->apiKey,
                ],
                'query' => $query,
            ]);

            $results = $this->transport->decode($response, Results::class);
        } finally {
            unset($query);
        }

        return $results->getFirstResult()?->toResponse() ?? Response::notFound();
    }

    /**
     * @see OneToMany\Geocoder\Contract\Bridge\ProviderInterface
     */
    #[\Override]
    public function reverse(Reverse $reverse): Response
    {
        $url = $this->url('geocode', 'location');

        $query = [
            'location.latitude' => $reverse->latitude,
            'location.longitude' => $reverse->longitude,
        ];

        try {
            $response = $this->transport->getRequest($url, [
                'headers' => [
                    'x-goog-api-key' => $this->apiKey,
                ],
                'query' => $query,
            ]);

            $results = $this->transport->decode($response, Results::class);
        } finally {
            unset($query);
        }

        return $results->getFirstResult()?->toResponse() ?? Response::notFound();
    }

    private function url(string ...$paths): string
    {
        return $this->transport->url(self::BASE_URL, $this->apiVersion, ...$paths);
    }
}
