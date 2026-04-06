<?php

declare(strict_types=1);

namespace BeehiveHub\SDK;

use BeehiveHub\SDK\Exceptions\BeehiveHubAPIError;
use BeehiveHub\SDK\Exceptions\BeehiveHubAuthenticationError;
use BeehiveHub\SDK\Exceptions\BeehiveHubNetworkError;
use BeehiveHub\SDK\Exceptions\BeehiveHubNotFoundError;
use BeehiveHub\SDK\Exceptions\BeehiveHubRateLimitError;
use BeehiveHub\SDK\Exceptions\BeehiveHubValidationError;

class HttpClient
{
    private string $baseUrl;
    private array $defaultHeaders;

    public function __construct(string $apiKey, string $environment = 'production')
    {
        $this->baseUrl = $environment === 'sandbox'
            ? Constants::API_SANDBOX_URL
            : Constants::API_PRODUCTION_URL;

        $this->defaultHeaders = Constants::defaultHeaders($apiKey);
    }

    public function request(string $method, string $path, ?array $body = null, ?array $query = null): mixed
    {
        $url = $this->baseUrl . $path;

        if ($query !== null) {
            $filtered = array_filter($query, fn($v) => $v !== null);
            if (!empty($filtered)) {
                $url .= '?' . http_build_query($filtered);
            }
        }

        $headerLines = [];
        foreach ($this->defaultHeaders as $key => $value) {
            $headerLines[] = "$key: $value";
        }

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER     => $headerLines,
            CURLOPT_CUSTOMREQUEST  => strtoupper($method),
            CURLOPT_TIMEOUT        => 30,
        ]);

        if ($body !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $response   = curl_exec($ch);
        $statusCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError  = curl_error($ch);

        curl_close($ch);

        if ($curlError) {
            throw new BeehiveHubNetworkError($curlError);
        }

        $decoded = ($response !== '' && $response !== false)
            ? json_decode($response, true)
            : null;

        if ($statusCode >= 200 && $statusCode < 300) {
            return $decoded;
        }

        $this->throwError($statusCode, $decoded);
    }

    private function throwError(int $statusCode, mixed $body): never
    {
        $message = (is_array($body) && isset($body['message'])) ? $body['message'] : 'Unknown error';
        $code    = is_array($body) ? ($body['code'] ?? null) : null;
        $details = is_array($body) ? ($body['details'] ?? null) : null;

        match (true) {
            $statusCode === 400 => throw new BeehiveHubValidationError($message, $statusCode, $code, $details),
            $statusCode === 401 => throw new BeehiveHubAuthenticationError('Invalid API key or authentication failed', $statusCode, $code, $details),
            $statusCode === 404 => throw new BeehiveHubNotFoundError($message, $statusCode, $code, $details),
            $statusCode === 429 => throw new BeehiveHubRateLimitError($message, $statusCode, $code, $details),
            default             => throw new BeehiveHubAPIError($message, $statusCode, $code, $details),
        };
    }
}
