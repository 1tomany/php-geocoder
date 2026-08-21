<?php

namespace OneToMany\Geocoder\Resource;

use OneToMany\Geocoder\Contract\Bridge\ProviderInterface;
use OneToMany\Geocoder\Exception\InvalidArgumentException;
use OneToMany\Geocoder\Vendor;

use function sprintf;

/**
 * @template T of ProviderInterface
 */
final readonly class Registry
{
    /**
     * @var array<non-empty-lowercase-string, T>
     */
    private array $providers;

    /**
     * @param iterable<T> $providers
     *
     * @throws InvalidArgumentException when a provider is already registered
     */
    public function __construct(iterable $providers)
    {
        $indexedProviders = [];

        foreach ($providers as $provider) {
            if (isset($indexedProviders[$provider::getVendor()->getValue()])) {
                throw new InvalidArgumentException(sprintf('The "%s" provider is already registered.', $provider::getVendor()->getValue()));
            }

            $indexedProviders[$provider::getVendor()->getValue()] = $provider;
        }

        $this->providers = $indexedProviders;
    }

    /**
     * @return T
     *
     * @throws InvalidArgumentException when a provider is not registered
     */
    public function get(Vendor $vendor): ProviderInterface
    {
        if (!isset($this->providers[$vendor->getValue()])) {
            throw new InvalidArgumentException(sprintf('The "%s" provider is not registered.', $vendor->getValue()));
        }

        return $this->providers[$vendor->getValue()];
    }
}
