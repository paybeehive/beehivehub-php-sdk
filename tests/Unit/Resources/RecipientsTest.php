<?php

declare(strict_types=1);

namespace BeehiveHub\SDK\Tests\Unit\Resources;

use BeehiveHub\SDK\HttpClient;
use BeehiveHub\SDK\Resources\Recipients;
use PHPUnit\Framework\TestCase;

class RecipientsTest extends TestCase
{
    private HttpClient $http;
    private Recipients $recipients;

    protected function setUp(): void
    {
        $this->http       = $this->createMock(HttpClient::class);
        $this->recipients = new Recipients($this->http);
    }

    public function testCreateCallsPostWithData(): void
    {
        $data = ['name' => 'Supplier'];

        $this->http->expects($this->once())
            ->method('request')
            ->with('POST', '/recipients', $data)
            ->willReturn(['id' => 1]);

        $result = $this->recipients->create($data);

        $this->assertSame(['id' => 1], $result);
    }

    public function testListCallsGetEndpoint(): void
    {
        $this->http->expects($this->once())
            ->method('request')
            ->with('GET', '/recipients')
            ->willReturn(['data' => []]);

        $result = $this->recipients->list();

        $this->assertSame(['data' => []], $result);
    }

    public function testGetCallsCorrectEndpoint(): void
    {
        $this->http->expects($this->once())
            ->method('request')
            ->with('GET', '/recipients/5')
            ->willReturn(['id' => 5]);

        $result = $this->recipients->get(5);

        $this->assertSame(['id' => 5], $result);
    }

    public function testUpdateCallsPatchEndpoint(): void
    {
        $data = ['name' => 'Updated Supplier'];

        $this->http->expects($this->once())
            ->method('request')
            ->with('PATCH', '/recipients/5', $data)
            ->willReturn(['id' => 5]);

        $result = $this->recipients->update(5, $data);

        $this->assertSame(['id' => 5], $result);
    }
}
