<?php

namespace OneToMany\Geocoder\Bridge\Google;

use OneToMany\Geocoder\Bridge\Common\Trait\DenormalizerTrait;
use OneToMany\Geocoder\Bridge\Common\Trait\HttpRequestTrait;
use OneToMany\Geocoder\Bridge\Google\Response\Error\Error;
use OneToMany\Geocoder\Bridge\Google\Response\Geocode\Results;
use OneToMany\Geocoder\Contract\Bridge\ProviderInterface;
use OneToMany\Geocoder\Exception\InvalidArgumentException;
use OneToMany\Geocoder\Exception\RuntimeException;
use OneToMany\Geocoder\Resource\Geocode;
use OneToMany\Geocoder\Resource\Response;
use OneToMany\Geocoder\Resource\Reverse;
use OneToMany\Geocoder\Vendor;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface as HttpClientResponseInterface;

use function array_filter;
use function implode;
use function json_validate;
use function sprintf;
use function trim;

final readonly class Provider implements ProviderInterface
{
    use DenormalizerTrait;
    use HttpRequestTrait;

    public const string BASE_URL = 'https://geocode.googleapis.com';

    /**
     * @param non-empty-string $apiVersion
     *
     * @throws InvalidArgumentException when the API key is empty
     */
    public function __construct(
        private HttpClientInterface $httpClient,
        private DenormalizerInterface $denormalizer,
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
        $query = array_filter([
            'address.addressLines' => $geocode->line,
            'address.locality' => $geocode->city,
            'address.postalCode' => $geocode->zip,
            'address.administrativeArea' => $geocode->state,
            'address.regionCode' => $geocode->country,
        ]);

        try {
            $response = $this->doGetRequest($this->url('geocode', 'address'), [
                'headers' => [
                    'x-goog-api-key' => $this->apiKey,
                ],
                'query' => $query,
            ]);

            $results = $this->doDenormalize($response, Results::class);
        } finally {
            unset($query);
        }

        return $results->getFirstResult()?->toResponse() ?? Response::createInvalid();
    }

    /**
     * @see OneToMany\Geocoder\Contract\Bridge\ProviderInterface
     */
    #[\Override]
    public function reverse(Reverse $reverse): Response
    {
        $query = [
            'location.latitude' => $reverse->latitude,
            'location.longitude' => $reverse->longitude,
        ];

        try {
            $response = $this->doGetRequest($this->url('geocode', 'location'), [
                'headers' => [
                    'x-goog-api-key' => $this->apiKey,
                ],
                'query' => $query,
            ]);

            $results = $this->doDenormalize($response, Results::class);
        } finally {
            unset($query);
        }

        return $results->getFirstResult()?->toResponse() ?? Response::createInvalid();
    }

    private function url(string ...$paths): string
    {
        return implode('/', [self::BASE_URL, $this->apiVersion, ...$paths]);
    }

    /**
     * @see OneToMany\Geocoder\Bridge\Common\Trait\HttpRequestTrait
     */
    private function doAssertSuccessfulRequest(
        HttpClientResponseInterface $response,
        int $expectedStatusCode = 200,
    ): HttpClientResponseInterface {
        try {
            $statusCode = $response->getStatusCode();

            if ($expectedStatusCode !== $statusCode) {
                $content = trim($response->getContent(false));

                if ('' === $content) {
                    $error = Error::fromStatusCode($statusCode);
                } elseif (json_validate($content)) {
                    $error = $this->doDenormalize($response, Error::class, [
                        UnwrappingDenormalizer::UNWRAP_PATH => '[error]',
                    ]);
                } else {
                    $error = new Error($content, $statusCode);
                }

                throw new RuntimeException($error->message, $statusCode);
            }
        } catch (HttpClientExceptionInterface $e) {
            throw new RuntimeException($e->getMessage(), previous: $e);
        }

        return $response;
    }
}
