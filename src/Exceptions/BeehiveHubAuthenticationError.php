<?php

declare(strict_types=1);

namespace BeehiveHub\SDK\Exceptions;

class BeehiveHubAuthenticationError extends BeehiveHubError
{
    public function __construct(
        string $message = 'Invalid API key or authentication failed',
        ?int $statusCode = 401,
        ?string $errorCode = null,
        mixed $details = null,
    ) {
        parent::__construct($message, $statusCode, $errorCode, $details);
    }
}
