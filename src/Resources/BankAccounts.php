<?php

declare(strict_types=1);

namespace BeehiveHub\SDK\Resources;

use BeehiveHub\SDK\HttpClient;

class BankAccounts
{
    public function __construct(private readonly HttpClient $http) {}

    public function create(int $recipientId, array $data): array
    {
        return $this->http->request('POST', "/recipients/{$recipientId}/bank-accounts", $data);
    }

    public function list(int $recipientId): array
    {
        return $this->http->request('GET', "/recipients/{$recipientId}/bank-accounts");
    }
}
