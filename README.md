# PHP Geocoder Library

This package provides a simple framework-independent geocoding library.

## Installation

Install the library using Composer:

```console
composer require 1tomany/php-geocoder
```

### Symfony Bundle

A [Symfony bundle](https://github.com/1tomany/php-geocoder-bundle) is available if you wish to integrate this library into your Symfony applications with autowiring and configuration support.

## Supported platforms

- Google
- Mock

## Usage

```php
<?php

use OneToMany\Geocoder\Bridge\Google\GoogleProvider;
use OneToMany\Geocoder\Bridge\Mock\MockProvider;
use OneToMany\Geocoder\Bridge\Transport;
use OneToMany\Geocoder\GeocoderClient;
use OneToMany\Geocoder\Resource\Geocode;
use OneToMany\Geocoder\Resource\Reverse;
use OneToMany\Geocoder\Vendor;

$transport = new Transport(
    createSymfonyHttpClient(),
    createSymfonySerializer(),
);

$googleApiKey = getenv('GOOGLE_API_KEY');

$geocoderClient = new GeocoderClient([
    new GoogleProvider(
        transport: $transport,
        apiKey: $googleApiKey,
    ),
    new MockProvider(),
]);

$response = $geocoderClient->geocode(
    Vendor::Google,
    new Geocode(
        street: '123 Main Street',
        unit: null,
        city: 'Dallas',
        zip: '75205',
        state: 'TX',
        country: null,
    ),
);

$response = $geocoderClient->reverse(
    Vendor::Google,
    new Reverse('32.10391494', '-96.3931030'),
);
```

## Credits

- [Vic Cherubini](https://github.com/viccherubini), [1:N Labs, LLC](https://1tomany.com)

## License

The MIT License
