<?php

namespace Unicodeveloper\Paystack\Services;

use Illuminate\Support\Facades\Request;
use Unicodeveloper\Paystack\Client\PaystackClient;
use Unicodeveloper\Paystack\Exceptions\PaystackRequestException;

/**
 * Class TransactionService
 *
 * Handles transaction-related operations with the Paystack API.
 *
 * @package Unicodeveloper\Paystack\Services
*/
class TransactionService
{
    /**
     * Paystack HTTP client instance.
     *
     * @var \Unicodeveloper\Paystack\Client\PaystackClient
    */
    protected PaystackClient $client;

    /**
     * TransactionService constructor.
     *
     * @param \Unicodeveloper\Paystack\Client\PaystackClient $client
    */
    public function __construct(PaystackClient $client)
    {
        $this->client = $client;
    }

    /**
     * Handle exceptions gracefully and format the result.
     *
     * @internal This method is not part of the public API and may change without notice.
     *
     * @internal This method is not part of the public API and may change without notice.
     *
     * @param callable $callback
     * @return array {
     *     @type bool   $status  Indicates success or failure.
     *     @type string $message Descriptive message or error.
     *     @type mixed  $data    The API response data or null on failure.
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
     * Initialize a transaction.
     *
     * Required payload fields:
     * - email: Customer's email address.
     * - amount: Amount in kobo (10000 = ₦100).
     *
     * Example:
     * ```php
     * [
     *     'email' => 'user@example.com',
     *     'amount' => 10000,
     *     'callback_url' => 'https://yourapp.com/callback'
     * ]
     * ```
     *
     * @param array $payload Transaction initialization data.
     * @return array Paystack response including authorization URL.
    */
    public function initialize(array $payload): array
    {
        return $this->handle(fn () => $this->client->post('transaction/initialize', $payload)->json());
    }

    /**
     * Verify a transaction by reference code.
     *
     * @param string $reference The transaction reference.
     * @return array Paystack verification result.
    */
    public function verify(string $reference): array
    {
        return $this->handle(fn () => $this->client->get("transaction/verify/{$reference}")->json());
    }

    /**
     * List transactions with pagination support.
     *
     * @param int $perPage Number of results per page.
     * @param int $page Current page number.
     * @return array Paginated list of transactions.
    */
    public function list(int $perPage = 50, int $page = 1): array
    {
        return $this->handle(fn () => $this->client->get("transaction?perPage={$perPage}&page={$page}")->json());
    }

    /**
     * Fetch a single transaction by its ID or reference.
     *
     * @param int|string $id Transaction ID or reference string.
     * @return array Transaction details.
    */
    public function fetch(int|string $id): array
    {
        return $this->handle(fn () => $this->client->get("transaction/{$id}")->json());
    }
}
