<?php

declare(strict_types=1);

namespace BeehiveHub\SDK\Tests\Unit\Resources;

use BeehiveHub\SDK\HttpClient;
use BeehiveHub\SDK\Resources\BankAccounts;
use PHPUnit\Framework\TestCase;

class BankAccountsTest extends TestCase
{
    private HttpClient $http;
    private BankAccounts $bankAccounts;

    protected function setUp(): void
    {
        $this->http         = $this->createMock(HttpClient::class);
        $this->bankAccounts = new BankAccounts($this->http);
    }

    public function testCreateCallsCorrectEndpoint(): void
    {
        $data = ['bank_code' => '001', 'agency' => '0001', 'account' => '12345-6'];

        $this->http->expects($this->once())
            ->method('request')
            ->with('POST', '/recipients/2/bank-accounts', $data)
            ->willReturn(['id' => 9]);

        $result = $this->bankAccounts->create(2, $data);

        $this->assertSame(['id' => 9], $result);
    }

    public function testListCallsCorrectEndpoint(): void
    {
        $this->http->expects($this->once())
            ->method('request')
            ->with('GET', '/recipients/2/bank-accounts')
            ->willReturn(['data' => []]);

        $result = $this->bankAccounts->list(2);

        $this->assertSame(['data' => []], $result);
    }
}
