<?php

namespace OneToMany\Geocoder\Bridge\Common\Trait;

use OneToMany\Geocoder\Exception\RuntimeException;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface as HttpClientResponseInterface;

trait HttpRequestTrait
{
    /**
     * @param int<100, 599> $expectedStatusCode
     */
    abstract private function doAssertSuccessfulRequest(HttpClientResponseInterface $response, int $expectedStatusCode = 200): HttpClientResponseInterface;

    /**
     * @param 'GET'|'POST'|'DELETE' $method
     * @param array<string, mixed> $options
     *
     * @throws RuntimeException when a non-successful HTTP status code is returned
     * @throws RuntimeException when a network or transport error occurs
     */
    private function doRequest(
        string $method,
        string $url,
        array $options = [],
    ): HttpClientResponseInterface {
        try {
            $response = $this->httpClient->request($method, $url, $options);
        } catch (HttpClientExceptionInterface $e) {
            throw new RuntimeException($e->getMessage(), previous: $e);
        }

        return $this->doAssertSuccessfulRequest($response);
    }

    /**
     * @param array<string, mixed> $options
     */
    private function doGetRequest(string $url, array $options = []): HttpClientResponseInterface
    {
        return $this->doRequest('GET', $url, $options);
    }
}
