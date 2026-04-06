<?php

declare(strict_types=1);

namespace BeehiveHub\SDK\Tests\Unit;

use BeehiveHub\SDK\Constants;
use BeehiveHub\SDK\Version;
use PHPUnit\Framework\TestCase;

class ConstantsTest extends TestCase
{
    public function testProductionUrl(): void
    {
        $this->assertSame('https://api.conta.paybeehive.com.br/v1', Constants::API_PRODUCTION_URL);
    }

    public function testSandboxUrl(): void
    {
        $this->assertSame('https://api.sandbox.hopysplit.com.br/v1', Constants::API_SANDBOX_URL);
    }

    public function testPaymentLinkProductionUrl(): void
    {
        $this->assertSame('https://link.conta.paybeehive.com.br', Constants::PAYMENT_LINK_PRODUCTION_URL);
    }

    public function testPaymentLinkSandboxUrl(): void
    {
        $this->assertSame('https://link.sandbox.hopysplit.com.br', Constants::PAYMENT_LINK_SANDBOX_URL);
    }

    public function testDefaultHeadersAuthorizationIsBasic(): void
    {
        $headers = Constants::defaultHeaders('my-api-key');

        $this->assertStringStartsWith('Basic ', $headers['Authorization']);
    }

    public function testDefaultHeadersAuthorizationEncodesKeyWithColonX(): void
    {
        $apiKey  = 'test-key-123';
        $headers = Constants::defaultHeaders($apiKey);

        $encoded  = base64_encode($apiKey . ':x');
        $this->assertSame('Basic ' . $encoded, $headers['Authorization']);
    }

    public function testDefaultHeadersContentType(): void
    {
        $headers = Constants::defaultHeaders('key');

        $this->assertSame('application/json', $headers['Content-Type']);
    }

    public function testDefaultHeadersUserAgentContainsSdkVersion(): void
    {
        $headers = Constants::defaultHeaders('key');

        $this->assertStringContainsString(Version::SDK_VERSION, $headers['User-Agent']);
        $this->assertStringContainsString('Beehive-PHP-SDK/', $headers['User-Agent']);
    }
}
