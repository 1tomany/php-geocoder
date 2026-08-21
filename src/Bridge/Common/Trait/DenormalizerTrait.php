<?php

namespace OneToMany\Geocoder\Bridge\Common\Trait;

use OneToMany\Geocoder\Exception\RuntimeException;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface as HttpClientResponseInterface;

trait DenormalizerTrait
{
    /**
     * @template R of object
     *
     * @param class-string<R> $type
     * @param array<string, mixed> $context
     *
     * @return R
     *
     * @throws RuntimeException when decoding or deserializing the response content fails
     */
    private function doDenormalize(
        HttpClientResponseInterface $response,
        string $type,
        array $context = [],
    ): object {
        try {
            return $this->denormalizer->denormalize($response->toArray(false), $type, null, $context);
        } catch (HttpClientExceptionInterface|SerializerExceptionInterface $e) {
            throw new RuntimeException($e->getMessage(), previous: $e);
        }
    }
}
