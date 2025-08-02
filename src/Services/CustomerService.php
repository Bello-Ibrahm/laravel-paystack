<?php

// src/Services/CustomerService.php
namespace Unicodeveloper\Paystack\Services;

use Unicodeveloper\Paystack\Client\PaystackClient;
use Unicodeveloper\Paystack\Exceptions\PaystackRequestException;

class CustomerService
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
        return $this->handle(fn() => $this->client->post('customer', $data)->json());
    }

    public function fetch(string $customerCode): array
    {
        return $this->handle(fn() => $this->client->get("customer/{$customerCode}")->json());
    }

    public function list(int $perPage = 50, int $page = 1): array
    {
        return $this->handle(fn() => $this->client->get("customer?perPage={$perPage}&page={$page}")->json());
    }
}