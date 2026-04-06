<?php

declare(strict_types=1);

namespace BeehiveHub\SDK;

use BeehiveHub\SDK\Resources\Balance;
use BeehiveHub\SDK\Resources\BankAccounts;
use BeehiveHub\SDK\Resources\Company;
use BeehiveHub\SDK\Resources\Customers;
use BeehiveHub\SDK\Resources\PaymentLinks;
use BeehiveHub\SDK\Resources\Recipients;
use BeehiveHub\SDK\Resources\Transactions;
use BeehiveHub\SDK\Resources\Transfers;
use InvalidArgumentException;

class BeehiveHubClient
{
    public readonly Transactions $transactions;
    public readonly Customers $customers;
    public readonly Balance $balance;
    public readonly Recipients $recipients;
    public readonly BankAccounts $bankAccounts;
    public readonly Transfers $transfers;
    public readonly Company $company;
    public readonly PaymentLinks $paymentLinks;

    /**
     * @param string $apiKey      Your BeehiveHub secret API key.
     * @param array  $options     Optional settings:
     *                            - 'environment' => 'production' | 'sandbox'
     */
    public function __construct(string $apiKey, array $options = [])
    {
        if (trim($apiKey) === '') {
            throw new InvalidArgumentException('API key is required to create a BeehiveHubClient.');
        }

        $environment = $options['environment'] ?? 'production';
        $http        = new HttpClient($apiKey, $environment);

        $this->transactions  = new Transactions($http);
        $this->customers     = new Customers($http);
        $this->balance       = new Balance($http);
        $this->recipients    = new Recipients($http);
        $this->bankAccounts  = new BankAccounts($http);
        $this->transfers     = new Transfers($http);
        $this->company       = new Company($http);
        $this->paymentLinks  = new PaymentLinks($http, $environment);
    }
}
