<?php

declare(strict_types=1);

namespace BeehiveHub\SDK\Exceptions;

use RuntimeException;

class BeehiveHubError extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly ?int $statusCode = null,
        public readonly ?string $errorCode = null,
        public readonly mixed $details = null,
    ) {
        parent::__construct($message);
    }

    public function toArray(): array
    {
        return [
            'message'    => $this->getMessage(),
            'statusCode' => $this->statusCode,
            'code'       => $this->errorCode,
            'details'    => $this->details,
        ];
    }
}
