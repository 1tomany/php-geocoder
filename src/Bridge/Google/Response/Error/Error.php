<?php

namespace OneToMany\Geocoder\Bridge\Google\Response\Error;

use function sprintf;
use function trim;

final readonly class Error
{
    public string $message;

    public function __construct(
        string $message,
        public int $code,
        public string $status = '',
    ) {
        $this->message = trim($message);
    }

    public static function fromStatusCode(int $statusCode): static
    {
        return new static(sprintf('The server returned an HTTP %d status.', $statusCode), $statusCode);
    }
}
