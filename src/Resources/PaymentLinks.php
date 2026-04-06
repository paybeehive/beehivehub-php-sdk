<?php

declare(strict_types=1);

namespace BeehiveHub\SDK\Resources;

use BeehiveHub\SDK\Constants;
use BeehiveHub\SDK\HttpClient;
use BeehiveHub\SDK\Utils;

class PaymentLinks
{
    private string $linkBaseUrl;

    public function __construct(
        private readonly HttpClient $http,
        string $environment = 'production',
    ) {
        $this->linkBaseUrl = $environment === 'sandbox'
            ? Constants::PAYMENT_LINK_SANDBOX_URL
            : Constants::PAYMENT_LINK_PRODUCTION_URL;
    }

    public function create(array $data): array
    {
        if (!isset($data['alias']) || $data['alias'] === '') {
            $data['alias'] = Utils::generateId(10, true);
        }

        $result = $this->http->request('POST', '/payment-links', $data);

        return $this->appendUrl($result);
    }

    public function list(): array
    {
        $result = $this->http->request('GET', '/payment-links');

        if (isset($result['data']) && is_array($result['data'])) {
            $result['data'] = array_map([$this, 'appendUrl'], $result['data']);
        }

        return $result;
    }

    public function get(int $id): array
    {
        $result = $this->http->request('GET', "/payment-links/{$id}");

        return $this->appendUrl($result);
    }

    public function update(int $id, array $data): array
    {
        $result = $this->http->request('PATCH', "/payment-links/{$id}", $data);

        return $this->appendUrl($result);
    }

    public function delete(int $id): void
    {
        $this->http->request('DELETE', "/payment-links/{$id}");
    }

    private function appendUrl(array $item): array
    {
        if (isset($item['alias'])) {
            $item['url'] = $this->linkBaseUrl . '/' . $item['alias'];
        }

        return $item;
    }
}
