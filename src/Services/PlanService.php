<?php

namespace Unicodeveloper\Paystack\Services;

use Unicodeveloper\Paystack\Client\PaystackClient;
use Unicodeveloper\Paystack\Exceptions\PaystackRequestException;

/**
 * Class PlanService
 *
 * Handles Paystack subscription plan operations such as creating, fetching, and listing plans.
 *
 * @package Unicodeveloper\Paystack\Services
*/
class PlanService
{
    /**
     * The Paystack API client instance.
     *
     * @var \Unicodeveloper\Paystack\Client\PaystackClient
    */
    protected PaystackClient $client;

    /**
     * PlanService constructor.
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
     * Create a new subscription plan on Paystack.
     *
     * Example payload:
     * ```php
     * [
     *     'name' => 'Monthly Gold Plan',
     *     'amount' => 100000, // in kobo
     *     'interval' => 'monthly',
     * ]
     * ```
     *
     * @param array $data The data required to create the plan.
     * @return array The response from Paystack API.
    */
    public function create(array $data): array
    {
        return $this->handle(fn () => $this->client->post('plan', $data)->json());
    }

    /**
     * Fetch a subscription plan by its code.
     *
     * @param string $planCode The plan code from Paystack.
     * @return array The response from Paystack API.
    */
    public function fetch(string $planCode): array
    {
        return $this->handle(fn () => $this->client->get("plan/{$planCode}")->json());
    }

    /**
     * List all subscription plans with pagination.
     *
     * @param int $perPage Number of plans per page.
     * @param int $page    The current page number.
     * @return array The response from Paystack API.
    */
    public function list(int $perPage = 50, int $page = 1): array
    {
        return $this->handle(fn () => $this->client->get("plan?perPage={$perPage}&page={$page}")->json());
    }
}
