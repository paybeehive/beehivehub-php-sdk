<?php

declare(strict_types=1);

namespace BeehiveHub\SDK\Tests\Unit;

use BeehiveHub\SDK\BeehiveHubClient;
use BeehiveHub\SDK\Resources\Balance;
use BeehiveHub\SDK\Resources\BankAccounts;
use BeehiveHub\SDK\Resources\Company;
use BeehiveHub\SDK\Resources\Customers;
use BeehiveHub\SDK\Resources\PaymentLinks;
use BeehiveHub\SDK\Resources\Recipients;
use BeehiveHub\SDK\Resources\Transactions;
use BeehiveHub\SDK\Resources\Transfers;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class BeehiveHubClientTest extends TestCase
{
    public function testEmptyApiKeyThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new BeehiveHubClient('');
    }

    public function testWhitespaceApiKeyThrowsException(): void
    {
        $this->expectException(InvalidArgumentException::class);

        new BeehiveHubClient('   ');
    }

    public function testValidClientExposesAllResources(): void
    {
        $client = new BeehiveHubClient('valid-api-key');

        $this->assertInstanceOf(Transactions::class, $client->transactions);
        $this->assertInstanceOf(Customers::class, $client->customers);
        $this->assertInstanceOf(Balance::class, $client->balance);
        $this->assertInstanceOf(Recipients::class, $client->recipients);
        $this->assertInstanceOf(BankAccounts::class, $client->bankAccounts);
        $this->assertInstanceOf(Transfers::class, $client->transfers);
        $this->assertInstanceOf(Company::class, $client->company);
        $this->assertInstanceOf(PaymentLinks::class, $client->paymentLinks);
    }

    public function testSandboxEnvironmentIsAccepted(): void
    {
        $client = new BeehiveHubClient('valid-api-key', ['environment' => 'sandbox']);

        $this->assertInstanceOf(BeehiveHubClient::class, $client);
    }

    public function testProductionEnvironmentIsAccepted(): void
    {
        $client = new BeehiveHubClient('valid-api-key', ['environment' => 'production']);

        $this->assertInstanceOf(BeehiveHubClient::class, $client);
    }
}
