<?php

declare(strict_types=1);

namespace BeehiveHub\SDK\Tests\Unit\Resources;

use BeehiveHub\SDK\HttpClient;
use BeehiveHub\SDK\Resources\Transactions;
use PHPUnit\Framework\TestCase;

class TransactionsTest extends TestCase
{
    private HttpClient $http;
    private Transactions $transactions;

    protected function setUp(): void
    {
        $this->http         = $this->createMock(HttpClient::class);
        $this->transactions = new Transactions($this->http);
    }

    public function testCreateCallsPostWithData(): void
    {
        $data = ['amount' => 1000, 'customer_id' => 5];

        $this->http->expects($this->once())
            ->method('request')
            ->with('POST', '/transactions', $data)
            ->willReturn(['id' => 1]);

        $result = $this->transactions->create($data);

        $this->assertSame(['id' => 1], $result);
    }

    public function testListCallsGetWithQueryParams(): void
    {
        $params = ['status' => 'paid', 'page' => 1];

        $this->http->expects($this->once())
            ->method('request')
            ->with('GET', '/transactions', null, $params)
            ->willReturn(['data' => []]);

        $result = $this->transactions->list($params);

        $this->assertSame(['data' => []], $result);
    }

    public function testListWithNoParams(): void
    {
        $this->http->expects($this->once())
            ->method('request')
            ->with('GET', '/transactions', null, [])
            ->willReturn(['data' => []]);

        $this->transactions->list();
    }

    public function testGetCallsCorrectEndpoint(): void
    {
        $this->http->expects($this->once())
            ->method('request')
            ->with('GET', '/transactions/42')
            ->willReturn(['id' => 42]);

        $result = $this->transactions->get(42);

        $this->assertSame(['id' => 42], $result);
    }

    public function testRefundWithoutAmountSendsNullBody(): void
    {
        $this->http->expects($this->once())
            ->method('request')
            ->with('POST', '/transactions/7/refund', null)
            ->willReturn(['status' => 'refunded']);

        $this->transactions->refund(7);
    }

    public function testRefundWithAmountSendsAmountInBody(): void
    {
        $this->http->expects($this->once())
            ->method('request')
            ->with('POST', '/transactions/7/refund', ['amount' => 500])
            ->willReturn(['status' => 'refunded']);

        $this->transactions->refund(7, 500);
    }

    public function testUpdateDeliveryCallsPatchEndpoint(): void
    {
        $data = ['tracking_code' => 'BR123'];

        $this->http->expects($this->once())
            ->method('request')
            ->with('PATCH', '/transactions/3/delivery', $data)
            ->willReturn(['id' => 3]);

        $result = $this->transactions->updateDelivery(3, $data);

        $this->assertSame(['id' => 3], $result);
    }
}
