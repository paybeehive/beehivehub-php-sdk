<?php

declare(strict_types=1);

namespace BeehiveHub\SDK;

class Constants
{
    public const API_PRODUCTION_URL = 'https://api.conta.paybeehive.com.br/v1';
    public const API_SANDBOX_URL = 'https://api.sandbox.hopysplit.com.br/v1';

    public const PAYMENT_LINK_PRODUCTION_URL = 'https://link.conta.paybeehive.com.br';
    public const PAYMENT_LINK_SANDBOX_URL = 'https://link.sandbox.hopysplit.com.br';

    public const DOCS_URL = 'https://docs.beehivehub.io';

    public static function defaultHeaders(string $apiKey): array
    {
        $credentials = base64_encode($apiKey . ':x');

        return [
            'Authorization' => 'Basic ' . $credentials,
            'Content-Type'  => 'application/json',
            'User-Agent'    => 'Beehive-PHP-SDK/' . Version::SDK_VERSION . ' PHP/' . PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION,
        ];
    }
}
