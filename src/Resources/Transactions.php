<?php

declare(strict_types=1);

namespace BeehiveHub\SDK\Resources;

use BeehiveHub\SDK\HttpClient;

class Transactions
{
    public function __construct(private readonly HttpClient $http) {}

    public function create(array $data): array
    {
        return $this->http->request('POST', '/transactions', $data);
    }

    public function list(array $params = []): array
    {
        return $this->http->request('GET', '/transactions', null, $params);
    }

    public function get(int $id): array
    {
        return $this->http->request('GET', "/transactions/{$id}");
    }

    public function refund(int $id, ?int $amount = null): array
    {
        $body = $amount !== null ? ['amount' => $amount] : null;

        return $this->http->request('POST', "/transactions/{$id}/refund", $body);
    }

    public function updateDelivery(int $id, array $data): array
    {
        return $this->http->request('PATCH', "/transactions/{$id}/delivery", $data);
    }
}
