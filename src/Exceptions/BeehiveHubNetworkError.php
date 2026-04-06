<?php

declare(strict_types=1);

namespace BeehiveHub\SDK\Exceptions;

class BeehiveHubNetworkError extends BeehiveHubError
{
    public function __construct(string $message)
    {
        parent::__construct($message, null, null, null);
    }
}
