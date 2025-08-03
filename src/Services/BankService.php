<?php

namespace Unicodeveloper\Paystack\Services;

use Unicodeveloper\Paystack\Client\PaystackClient;
use Unicodeveloper\Paystack\Exceptions\PaystackRequestException;

/**
 * Class BankService
 *
 * Handles Paystack bank-related operations such as listing banks and resolving bank accounts.
 *
 * @package Unicodeveloper\Paystack\Services
*/
class BankService
{
    /**
     * The Paystack API client instance.
     *
     * @var \Unicodeveloper\Paystack\Client\PaystackClient
    */
    protected PaystackClient $client;

    /**
     * BankService constructor.
     *
     * @param \Unicodeveloper\Paystack\Client\PaystackClient $client
    */
    public function __construct(PaystackClient $client)
    {
        $this->client = $client;
    }

    /**
     * Wrap API calls with error handling.
     *
     * @internal This method is not part of the public API and may change without notice.
     *
     * @param callable $callback
     * @return array {
     *     @type bool   $status  Whether the request was successful.
     *     @type string $message Message or error information.
     *     @type mixed  $data    The response data or null on failure.
     * }
    */
    protected function handle(callable $callback): array
    {
        try {
            return $callback();
        } catch (PaystackRequestException $e) {
            return [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Retrieve a list of available banks from Paystack.
     *
     * @return array The response from Paystack API.
    */
    public function list(): array
    {
        return $this->handle(fn () => $this->client->get('bank')->json());
    }

    /**
     * Resolve a bank account number and bank code to retrieve account details.
     *
     * Example payload:
     * ```php
     * [
     *     'account_number' => '1234567890',
     *     'bank_code' => '058'
     * ]
     * ```
     *
     * @param array $data An associative array containing `account_number` and `bank_code`.
     * @return array The response from Paystack API.
    */
    public function resolveAccount(array $data): array
    {
        return $this->handle(fn () => $this->client->get('bank/resolve', $data)->json());
    }
}
