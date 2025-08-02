<?php
// src/Services/PlanService.php

namespace Unicodeveloper\Paystack\Services;

use Unicodeveloper\Paystack\Client\PaystackClient;
use Unicodeveloper\Paystack\Exceptions\PaystackRequestException;

class PlanService
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
        return $this->handle(fn() => $this->client->post('plan', $data)->json());
    }

    public function fetch(string $planCode): array
    {
        return $this->handle(fn() => $this->client->get("plan/{$planCode}")->json());
    }

    public function list(int $perPage = 50, int $page = 1): array
    {
        return $this->handle(fn() => $this->client->get("plan?perPage={$perPage}&page={$page}")->json());
    }
}