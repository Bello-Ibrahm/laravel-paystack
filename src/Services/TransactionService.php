<?php

namespace Unicodeveloper\Paystack\Services;

use Illuminate\Support\Facades\Request;
use Unicodeveloper\Paystack\Client\PaystackClient;
use Unicodeveloper\Paystack\Exceptions\PaystackRequestException;
// use Unicodeveloper\Paystack\Paystack;

class TransactionService
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

    public function initialize(array $payload): array
    {
        return $this->handle(fn() => $this->client->post('transaction/initialize', $payload)->json());
    }

    public function verify(string $reference): array
    {
        return $this->handle(fn() => $this->client->get("transaction/verify/{$reference}")->json());
    }

    public function list(int $perPage = 50, int $page = 1): array
    {
        return $this->handle(fn() => $this->client->get("transaction?perPage={$perPage}&page={$page}")->json());
    }

    public function fetch(int|string $id): array
    {
        return $this->handle(fn() => $this->client->get("transaction/{$id}")->json());
    }
}
