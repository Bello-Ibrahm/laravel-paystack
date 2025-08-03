<?php

namespace Unicodeveloper\Paystack\Services;

use Unicodeveloper\Paystack\Client\PaystackClient;
use Unicodeveloper\Paystack\Exceptions\PaystackRequestException;

/**
 * Class CustomerService
 *
 * Handles Paystack operations related to customers such as creating, fetching, and listing.
 *
 * @package Unicodeveloper\Paystack\Services
*/
class CustomerService
{
    /**
     * The Paystack API client instance.
     *
     * @var \Unicodeveloper\Paystack\Client\PaystackClient
    */
    protected PaystackClient $client;

    /**
     * CustomerService constructor.
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
     * Create a new customer on Paystack.
     *
     * Example payload:
     * ```php
     * [
     *     'email' => 'customer@example.com',
     *     'first_name' => 'John',
     *     'last_name' => 'Doe',
     *     'phone' => '08012345678'
     * ]
     * ```
     *
     * @param array $data Customer data for creation.
     * @return array The response from Paystack API.
    */
    public function create(array $data): array
    {
        return $this->handle(fn () => $this->client->post('customer', $data)->json());
    }

    /**
     * Fetch a customer by their customer code.
     *
     * @param string $customerCode The unique code identifying the customer.
     * @return array The response from Paystack API.
    */
    public function fetch(string $customerCode): array
    {
        return $this->handle(fn () => $this->client->get("customer/{$customerCode}")->json());
    }

    /**
     * Retrieve a paginated list of customers.
     *
     * @param int $perPage Number of customers per page (default: 50).
     * @param int $page Page number to retrieve (default: 1).
     * @return array The response from Paystack API.
    */
    public function list(int $perPage = 50, int $page = 1): array
    {
        return $this->handle(fn () => $this->client->get("customer?perPage={$perPage}&page={$page}")->json());
    }
}
