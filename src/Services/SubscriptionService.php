<?php

// src/Services/SubscriptionService.php
namespace Unicodeveloper\Paystack\Services;

use Unicodeveloper\Paystack\Client\PaystackClient;
use Unicodeveloper\Paystack\Exceptions\PaystackRequestException;

class SubscriptionService
{
    protected PaystackClient $client;

    public function __construct(PaystackClient $client)
    {
        $this->client = $client;
    }

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


    public function create(array $data): array
    {
        return $this->handle(fn() => $this->client->post('subscription', $data)->json());
    }

    public function disable(array $data): array
    {
        return $this->handle(fn() => $this->client->post('subscription/disable', $data)->json());
    }

    public function enable(array $data): array
    {
        return $this->handle(fn() => $this->client->post('subscription/enable', $data)->json());
    }

    public function fetch(string $subscriptionCode): array
    {
        return $this->handle(fn() => $this->client->get("subscription/{$subscriptionCode}")->json());
    }

    public function list(array $params = []): array
    {
        return $this->handle(fn() => $this->client->get('subscription', $params)->json());
    }

}
