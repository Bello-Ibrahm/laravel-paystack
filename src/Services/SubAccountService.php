<?php

namespace Unicodeveloper\Paystack\Services;

use Unicodeveloper\Paystack\Client\PaystackClient;
use Unicodeveloper\Paystack\Exceptions\PaystackRequestException;

/**
 * Class SubAccountService
 *
 * Manages Paystack subaccounts for split payments.
 *
 * @package Unicodeveloper\Paystack\Services
*/
class SubAccountService
{
    /**
     * The Paystack API client instance.
     *
     * @var \Unicodeveloper\Paystack\Client\PaystackClient
    */
    protected PaystackClient $client;

    /**
     * SubAccountService constructor.
     *
     * @param \Unicodeveloper\Paystack\Client\PaystackClient $client
    */
    public function __construct(PaystackClient $client)
    {
        $this->client = $client;
    }

    /**
     * Wrap API calls with exception handling.
     *
     * @internal This method is not part of the public API and may change without notice.
     *
     * @param callable $callback
     * @return array {
     *     @type bool   $status  Whether the request was successful.
     *     @type string $message Error or success message.
     *     @type mixed  $data    Response data or null if failed.
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
     * Create a new subaccount on Paystack.
     *
     * Example payload:
     * ```php
     * [
     *     'business_name' => 'My Business',
     *     'settlement_bank' => 'Access Bank',
     *     'account_number' => '1234567890',
     *     'percentage_charge' => 10.5
     * ]
     * ```
     *
     * @param array $data Data for creating the subaccount.
     * @return array The response from Paystack API.
    */
    public function create(array $data): array
    {
        return $this->handle(fn () => $this->client->post('subaccount', $data)->json());
    }

    /**
     * Fetch a specific subaccount by its code.
     *
     * @param string $subaccountCode The unique code of the subaccount.
     * @return array The response from Paystack API.
    */
    public function fetch(string $subaccountCode): array
    {
        return $this->handle(fn () => $this->client->get("subaccount/{$subaccountCode}")->json());
    }

    /**
     * List all subaccounts.
     *
     * @return array The response from Paystack API.
    */
    public function list(): array
    {
        return $this->handle(fn () => $this->client->get("subaccount")->json());
    }
}
