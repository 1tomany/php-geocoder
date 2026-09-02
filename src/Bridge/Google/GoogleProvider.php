<?php

namespace OneToMany\Geocoder\Bridge\Google;

use OneToMany\Geocoder\Bridge\Google\Response\Geocode\Results;
use OneToMany\Geocoder\Bridge\Transport;
use OneToMany\Geocoder\Contract\Bridge\ProviderInterface;
use OneToMany\Geocoder\Exception\DomainException;
use OneToMany\Geocoder\GeocodingVendor;
use OneToMany\Geocoder\Resource\ForwardGeocode;
use OneToMany\Geocoder\Resource\Response;
use OneToMany\Geocoder\Resource\ReverseGeocode;

use function sprintf;
use function trim;

final readonly class GoogleProvider implements ProviderInterface
{
    public const string BASE_URL = 'https://geocode.googleapis.com';

    /**
     * @param non-empty-string $apiKey
     * @param non-empty-string $apiVersion
     *
     * @throws DomainException when the API key is empty
     */
    public function __construct(
        private Transport $transport,
        #[\SensitiveParameter] private string $apiKey,
        private string $apiVersion = 'v4',
    ) {
        if ('' === trim($this->apiKey)) {
            throw new DomainException(sprintf('The %s API key cannot be empty.', self::getVendor()->getName()));
        }
    }

    /**
     * @see OneToMany\Geocoder\Contract\Bridge\ProviderInterface
     */
    #[\Override]
    public static function getVendor(): GeocodingVendor
    {
        return GeocodingVendor::Google;
    }

    /**
     * @see OneToMany\Geocoder\Contract\Bridge\ProviderInterface
     */
    #[\Override]
    public function forward(ForwardGeocode $request): Response
    {
        $url = $this->url('geocode', 'address');

        $query = [
            'address.addressLines' => $request->line,
        ];

        if (null !== $city = $request->city) {
            $query['address.locality'] = $city;
        }

        if (null !== $zip = $request->zip) {
            $query['address.postalCode'] = $zip;
        }

        if (null !== $state = $request->state) {
            $query['address.administrativeArea'] = $state;
        }

        if (null !== $country = $request->country) {
            $query['address.regionCode'] = $country;
        }

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
    public function reverse(ReverseGeocode $request): Response
    {
        $url = $this->url('geocode', 'location');

        $query = [
            'location.latitude' => $request->latitude,
            'location.longitude' => $request->longitude,
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
