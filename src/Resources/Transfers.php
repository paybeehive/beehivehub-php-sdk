<?php

declare(strict_types=1);

namespace BeehiveHub\SDK\Resources;

use BeehiveHub\SDK\HttpClient;

class Transfers
{
    public function __construct(private readonly HttpClient $http) {}

    public function create(array $data): array
    {
        return $this->http->request('POST', '/transfers', $data);
    }

    public function get(int $id): array
    {
        return $this->http->request('GET', "/transfers/{$id}");
    }
}
