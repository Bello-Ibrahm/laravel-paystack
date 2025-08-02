<?php

// src/Services/PageService.php
namespace Unicodeveloper\Paystack\Services;

use Unicodeveloper\Paystack\Client\PaystackClient;
use Unicodeveloper\Paystack\Exceptions\PaystackRequestException;

class PageService
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
        return $this->handle(fn() => $this->client->post('page', $data)->json());

    }

    public function fetch(string $pageId): array
    {
        return $this->handle(fn() => $this->client->get("page/{$pageId}")->json());
    }

    public function list(): array
    {
        return $this->handle(fn() => $this->client->get("page")->json());
    }
}
