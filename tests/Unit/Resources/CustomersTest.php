<?php

declare(strict_types=1);

namespace BeehiveHub\SDK\Tests\Unit\Resources;

use BeehiveHub\SDK\HttpClient;
use BeehiveHub\SDK\Resources\Customers;
use PHPUnit\Framework\TestCase;

class CustomersTest extends TestCase
{
    private HttpClient $http;
    private Customers $customers;

    protected function setUp(): void
    {
        $this->http      = $this->createMock(HttpClient::class);
        $this->customers = new Customers($this->http);
    }

    public function testCreateCallsPostWithData(): void
    {
        $data = ['name' => 'John', 'email' => 'john@example.com'];

        $this->http->expects($this->once())
            ->method('request')
            ->with('POST', '/customers', $data)
            ->willReturn(['id' => 10]);

        $result = $this->customers->create($data);

        $this->assertSame(['id' => 10], $result);
    }

    public function testListCallsGetWithQueryParams(): void
    {
        $params = ['email' => 'john@example.com'];

        $this->http->expects($this->once())
            ->method('request')
            ->with('GET', '/customers', null, $params)
            ->willReturn(['data' => []]);

        $this->customers->list($params);
    }

    public function testGetCallsCorrectEndpoint(): void
    {
        $this->http->expects($this->once())
            ->method('request')
            ->with('GET', '/customers/99')
            ->willReturn(['id' => 99]);

        $result = $this->customers->get(99);

        $this->assertSame(['id' => 99], $result);
    }
}
