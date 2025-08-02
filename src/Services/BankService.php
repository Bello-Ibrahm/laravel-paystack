<?php
// src/Services/BankService.php

namespace Unicodeveloper\Paystack\Services;

use Unicodeveloper\Paystack\Client\PaystackClient;
use Unicodeveloper\Paystack\Exceptions\PaystackRequestException;

class BankService
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

    public function list(): array
    {
        return $this->handle(fn() => $this->client->get('bank')->json());
    }

    public function resolveAccount(array $data): array
    {
        return $this->handle(fn() => $this->client->get('bank/resolve', $data)->json());
    }
}
