<?php

declare(strict_types=1);

namespace BeehiveHub\SDK\Resources;

use BeehiveHub\SDK\HttpClient;

class Company
{
    public function __construct(private readonly HttpClient $http) {}

    public function get(): array
    {
        return $this->http->request('GET', '/company');
    }

    public function update(array $data): array
    {
        return $this->http->request('PATCH', '/company', $data);
    }
}
