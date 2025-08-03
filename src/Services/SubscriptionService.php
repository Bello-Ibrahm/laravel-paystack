<?php

namespace Unicodeveloper\Paystack\Services;

use Unicodeveloper\Paystack\Client\PaystackClient;
use Unicodeveloper\Paystack\Exceptions\PaystackRequestException;

/**
 * Class SubscriptionService
 *
 * Handles recurring billing via subscriptions on Paystack.
 *
 * @package Unicodeveloper\Paystack\Services
*/
class SubscriptionService
{
    /**
     * The Paystack API client instance.
     *
     * @var \Unicodeveloper\Paystack\Client\PaystackClient
    */
    protected PaystackClient $client;

    /**
     * SubscriptionService constructor.
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
     * Create a subscription between a customer and a plan.
     *
     * Example payload:
     * ```php
     * [
     *     'customer' => 'CUS_xxxxxxx',
     *     'plan' => 'PLN_xxxxxxx',
     *     'authorization' => 'AUTH_xxxxxxx' // Optional if email token is used
     * ]
     * ```
     *
     * @param array $payload Subscription creation data.
     * @return array The response from Paystack API.
    */
    public function create(array $payload): array
    {
        return $this->handle(fn () => $this->client->post('subscription', $payload)->json());
    }

    /**
     * Disable a subscription by customer code or email token.
     *
     * Example payload:
     * ```php
     * [
     *     'code' => 'SUB_xxxxxxx',
     *     'token' => 'email_token_xxxxxxx'
     * ]
     * ```
     *
     * @param array $payload Disable payload.
     * @return array The response from Paystack API.
    */
    public function disable(array $payload): array
    {
        return $this->handle(fn () => $this->client->post('subscription/disable', $payload)->json());
    }

    /**
     * Enable a subscription using code and token.
     *
     * Example payload:
     * ```php
     * [
     *     'code' => 'SUB_xxxxxxx',
     *     'token' => 'email_token_xxxxxxx'
     * ]
     * ```
     *
     * @param array $payload Enable payload.
     * @return array The response from Paystack API.
    */
    public function enable(array $payload): array
    {
        return $this->handle(fn () => $this->client->post('subscription/enable', $payload)->json());
    }

    /**
     * Fetch details of a specific subscription by code.
     *
     * @param string $subscriptionCode The subscription code.
     * @return array The response from Paystack API.
    */
    public function fetch(string $subscriptionCode): array
    {
        return $this->handle(fn () => $this->client->get("subscription/{$subscriptionCode}")->json());
    }

    /**
     * List all subscriptions or filter them.
     *
     * Example filters:
     * ```php
     * [
     *     'customer' => 'CUS_xxxxxxx',
     *     'plan' => 'PLN_xxxxxxx',
     *     'perPage' => 50,
     *     'page' => 1
     * ]
     * ```
     *
     * @param array $params Optional query parameters.
     * @return array The response from Paystack API.
    */
    public function list(array $params = []): array
    {
        return $this->handle(fn () => $this->client->get('subscription', $params)->json());
    }
}
