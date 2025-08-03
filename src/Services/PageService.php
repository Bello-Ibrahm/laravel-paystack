<?php

namespace Unicodeveloper\Paystack\Services;

use Unicodeveloper\Paystack\Client\PaystackClient;
use Unicodeveloper\Paystack\Exceptions\PaystackRequestException;

/**
 * Class PageService
 *
 * Handles Paystack Payment Page operations such as creating, fetching, and listing pages.
 *
 * @package Unicodeveloper\Paystack\Services
*/
class PageService
{
    /**
     * The Paystack API client instance.
     *
     * @var \Unicodeveloper\Paystack\Client\PaystackClient
    */
    protected PaystackClient $client;

    /**
     * PageService constructor.
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
     * Create a new Payment Page on Paystack.
     *
     * Example payload:
     * ```php
     * [
     *     'name' => 'Special Offer',
     *     'description' => 'Limited time only',
     *     'amount' => 500000 // in kobo
     * ]
     * ```
     *
     * @param array $data Data required to create the page.
     * @return array The response from Paystack API.
    */
    public function create(array $data): array
    {
        return $this->handle(fn() => $this->client->post('page', $data)->json());
    }

    /**
     * Fetch a single Payment Page by its ID or slug.
     *
     * @param string $pageId The page ID or slug.
     * @return array The response from Paystack API.
    */
    public function fetch(string $pageId): array
    {
        return $this->handle(fn() => $this->client->get("page/{$pageId}")->json());
    }

    /**
     * Retrieve a list of all Payment Pages.
     *
     * @return array The response from Paystack API.
    */
    public function list(): array
    {
        return $this->handle(fn() => $this->client->get("page")->json());
    }
}
