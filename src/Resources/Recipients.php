<?php

declare(strict_types=1);

namespace BeehiveHub\SDK\Resources;

use BeehiveHub\SDK\HttpClient;

class Recipients
{
    public function __construct(private readonly HttpClient $http) {}

    public function create(array $data): array
    {
        return $this->http->request('POST', '/recipients', $data);
    }

    public function list(): array
    {
        return $this->http->request('GET', '/recipients');
    }

    public function get(int $id): array
    {
        return $this->http->request('GET', "/recipients/{$id}");
    }

    public function update(int $id, array $data): array
    {
        return $this->http->request('PATCH', "/recipients/{$id}", $data);
    }
}
