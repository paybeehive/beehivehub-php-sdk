<?php

declare(strict_types=1);

namespace BeehiveHub\SDK\Tests\Unit\Resources;

use BeehiveHub\SDK\HttpClient;
use BeehiveHub\SDK\Resources\Transfers;
use PHPUnit\Framework\TestCase;

class TransfersTest extends TestCase
{
    private HttpClient $http;
    private Transfers $transfers;

    protected function setUp(): void
    {
        $this->http      = $this->createMock(HttpClient::class);
        $this->transfers = new Transfers($this->http);
    }

    public function testCreateCallsPostWithData(): void
    {
        $data = ['amount' => 2000, 'recipient_id' => 3];

        $this->http->expects($this->once())
            ->method('request')
            ->with('POST', '/transfers', $data)
            ->willReturn(['id' => 8]);

        $result = $this->transfers->create($data);

        $this->assertSame(['id' => 8], $result);
    }

    public function testGetCallsCorrectEndpoint(): void
    {
        $this->http->expects($this->once())
            ->method('request')
            ->with('GET', '/transfers/8')
            ->willReturn(['id' => 8]);

        $result = $this->transfers->get(8);

        $this->assertSame(['id' => 8], $result);
    }
}
