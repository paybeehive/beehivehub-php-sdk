<?php

declare(strict_types=1);

namespace BeehiveHub\SDK\Tests\Unit\Resources;

use BeehiveHub\SDK\HttpClient;
use BeehiveHub\SDK\Resources\Balance;
use PHPUnit\Framework\TestCase;

class BalanceTest extends TestCase
{
    public function testGetCallsCorrectEndpoint(): void
    {
        $http    = $this->createMock(HttpClient::class);
        $balance = new Balance($http);

        $http->expects($this->once())
            ->method('request')
            ->with('GET', '/balance/available')
            ->willReturn(['amount' => 5000]);

        $result = $balance->get();

        $this->assertSame(['amount' => 5000], $result);
    }
}
