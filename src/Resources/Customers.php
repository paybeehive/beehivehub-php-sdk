<?php

declare(strict_types=1);

namespace BeehiveHub\SDK\Resources;

use BeehiveHub\SDK\HttpClient;

class Customers
{
    public function __construct(private readonly HttpClient $http) {}

    public function create(array $data): array
    {
        return $this->http->request('POST', '/customers', $data);
    }

    /**
     * List customers. Note: email parameter is required by the API.
     */
    public function list(array $params = []): array
    {
        return $this->http->request('GET', '/customers', null, $params);
    }

    public function get(int $id): array
    {
        return $this->http->request('GET', "/customers/{$id}");
    }
}
