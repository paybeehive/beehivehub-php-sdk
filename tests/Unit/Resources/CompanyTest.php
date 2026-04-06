<?php

declare(strict_types=1);

namespace BeehiveHub\SDK\Tests\Unit\Resources;

use BeehiveHub\SDK\HttpClient;
use BeehiveHub\SDK\Resources\Company;
use PHPUnit\Framework\TestCase;

class CompanyTest extends TestCase
{
    private HttpClient $http;
    private Company $company;

    protected function setUp(): void
    {
        $this->http    = $this->createMock(HttpClient::class);
        $this->company = new Company($this->http);
    }

    public function testGetCallsCorrectEndpoint(): void
    {
        $this->http->expects($this->once())
            ->method('request')
            ->with('GET', '/company')
            ->willReturn(['id' => 1, 'name' => 'Acme']);

        $result = $this->company->get();

        $this->assertSame(['id' => 1, 'name' => 'Acme'], $result);
    }

    public function testUpdateCallsPatchEndpoint(): void
    {
        $data = ['name' => 'New Name'];

        $this->http->expects($this->once())
            ->method('request')
            ->with('PATCH', '/company', $data)
            ->willReturn(['id' => 1, 'name' => 'New Name']);

        $result = $this->company->update($data);

        $this->assertSame(['id' => 1, 'name' => 'New Name'], $result);
    }
}
