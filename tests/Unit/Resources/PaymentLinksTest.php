<?php

declare(strict_types=1);

namespace BeehiveHub\SDK\Tests\Unit\Resources;

use BeehiveHub\SDK\Constants;
use BeehiveHub\SDK\HttpClient;
use BeehiveHub\SDK\Resources\PaymentLinks;
use PHPUnit\Framework\TestCase;

class PaymentLinksTest extends TestCase
{
    private HttpClient $http;

    protected function setUp(): void
    {
        $this->http = $this->createMock(HttpClient::class);
    }

    private function resource(string $environment = 'production'): PaymentLinks
    {
        return new PaymentLinks($this->http, $environment);
    }

    // --- create ---

    public function testCreateGeneratesAliasWhenMissing(): void
    {
        $this->http->expects($this->once())
            ->method('request')
            ->with('POST', '/payment-links', $this->callback(function (array $data): bool {
                return isset($data['alias']) && strlen($data['alias']) === 10;
            }))
            ->willReturnCallback(fn(string $m, string $p, array $data) => $data);

        $result = $this->resource()->create(['title' => 'My Link']);

        $this->assertArrayHasKey('alias', $result);
    }

    public function testCreateKeepsExistingAlias(): void
    {
        $this->http->expects($this->once())
            ->method('request')
            ->with('POST', '/payment-links', $this->callback(fn(array $d) => $d['alias'] === 'MY-ALIAS'))
            ->willReturn(['alias' => 'MY-ALIAS']);

        $result = $this->resource()->create(['alias' => 'MY-ALIAS']);

        $this->assertSame('MY-ALIAS', $result['alias']);
    }

    public function testCreateAppendsProductionUrl(): void
    {
        $this->http->method('request')->willReturn(['alias' => 'abc123']);

        $result = $this->resource('production')->create(['alias' => 'abc123']);

        $this->assertSame(Constants::PAYMENT_LINK_PRODUCTION_URL . '/abc123', $result['url']);
    }

    public function testCreateAppendsSandboxUrl(): void
    {
        $this->http->method('request')->willReturn(['alias' => 'abc123']);

        $result = $this->resource('sandbox')->create(['alias' => 'abc123']);

        $this->assertSame(Constants::PAYMENT_LINK_SANDBOX_URL . '/abc123', $result['url']);
    }

    public function testCreateDoesNotAppendUrlWhenNoAlias(): void
    {
        $this->http->method('request')->willReturn(['id' => 1]);

        $result = $this->resource()->create(['title' => 'Link']);

        $this->assertArrayNotHasKey('url', $result);
    }

    // --- list ---

    public function testListAppendsUrlToEachItem(): void
    {
        $this->http->method('request')->willReturn([
            'data' => [
                ['id' => 1, 'alias' => 'link-a'],
                ['id' => 2, 'alias' => 'link-b'],
            ],
        ]);

        $result = $this->resource()->list();

        $this->assertSame(Constants::PAYMENT_LINK_PRODUCTION_URL . '/link-a', $result['data'][0]['url']);
        $this->assertSame(Constants::PAYMENT_LINK_PRODUCTION_URL . '/link-b', $result['data'][1]['url']);
    }

    public function testListWithoutDataKeyIsReturnedAsIs(): void
    {
        $this->http->method('request')->willReturn(['error' => 'none']);

        $result = $this->resource()->list();

        $this->assertSame(['error' => 'none'], $result);
    }

    // --- get ---

    public function testGetAppendsUrl(): void
    {
        $this->http->expects($this->once())
            ->method('request')
            ->with('GET', '/payment-links/3')
            ->willReturn(['id' => 3, 'alias' => 'test-alias']);

        $result = $this->resource()->get(3);

        $this->assertSame(Constants::PAYMENT_LINK_PRODUCTION_URL . '/test-alias', $result['url']);
    }

    // --- update ---

    public function testUpdateCallsPatchAndAppendsUrl(): void
    {
        $data = ['title' => 'Updated'];

        $this->http->expects($this->once())
            ->method('request')
            ->with('PATCH', '/payment-links/4', $data)
            ->willReturn(['id' => 4, 'alias' => 'upd-alias']);

        $result = $this->resource()->update(4, $data);

        $this->assertSame(Constants::PAYMENT_LINK_PRODUCTION_URL . '/upd-alias', $result['url']);
    }

    // --- delete ---

    public function testDeleteCallsDeleteEndpoint(): void
    {
        $this->http->expects($this->once())
            ->method('request')
            ->with('DELETE', '/payment-links/5');

        $this->resource()->delete(5);
    }
}
