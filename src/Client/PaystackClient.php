<?php

namespace Unicodeveloper\Paystack\Client;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Unicodeveloper\Paystack\Exceptions\PaystackRequestException;

/**
 * Class PaystackClient
 *
 * Handles HTTP communication with the Paystack API.
*/
class PaystackClient
{
    protected string $baseUrl;
    protected string $secretKey;

    /**
     * PaystackClient constructor.
     *
     * @param string|null $secretKey  Optional API secret key.
     * @param string|null $baseUrl    Optional API base URL.
    */
    public function __construct(?string $secretKey = '', ?string $baseUrl = '')
    {
        $this->baseUrl = $baseUrl ?: config('paystack.paymentUrl', 'https://api.paystack.co');
        $this->secretKey = $secretKey ?: config('paystack.secretKey');
    }

    /**
     * Get a configured HTTP client for sending requests to Paystack.
     *
     * @internal
     * @return \Illuminate\Http\Client\PendingRequest
    */
    protected function client(): \Illuminate\Http\Client\PendingRequest
    {
        return Http::retry(3, 150)
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Accept'        => 'application/json',
                'Content-Type'  => 'application/json',
            ])
            ->withOptions([
                'http_errors' => false,
                'version'     => 1.1,
                'verify'      => true, // Set it to false for local debug
            ]);
    }

    /**
     * Handle API response errors.
     *
     * @internal
     * @param Response $response
     * @return Response
     *
     * @throws PaystackRequestException
    */
    protected function handleErrors(Response $response): Response
    {
        if (! $response->successful()) {
            $message = $response->json('message') ?? 'Paystack request failed.';
            // \Log::error('Paystack API error', ['response' => $response->body()]);
            throw new PaystackRequestException($message, $response);
        }

        return $response;
    }

    /**
     * Make an HTTP request to Paystack.
     *
     * @internal
     * @param string $method
     * @param string $endpoint
     * @param array $data
     * @return Response
     *
     * @throws PaystackRequestException
    */
    protected function request(string $method, string $endpoint, array $data = []): Response
    {
        $url = $this->baseUrl . '/' . ltrim($endpoint, '/');
        $response = $this->client()->{$method}($url, $data);

        return $this->handleErrors($response);
    }

    /**
     * Send a GET request to a Paystack API endpoint.
     *
     * @param string $endpoint
     * @return Response
     *
     * @throws PaystackRequestException
    */
    public function get(string $endpoint): Response
    {
        return $this->request('get', $endpoint);
    }

    /**
     * Send a POST request to a Paystack API endpoint.
     *
     * @param string $endpoint
     * @param array $data
     * @return Response
     *
     * @throws PaystackRequestException
    */
    public function post(string $endpoint, array $data): Response
    {
        return $this->request('post', $endpoint, $data);
    }

    /**
     * Send a PUT request to a Paystack API endpoint.
     *
     * @param string $endpoint
     * @param array $data
     * @return Response
     *
     * @throws PaystackRequestException
    */
    public function put(string $endpoint, array $data): Response
    {
        return $this->request('put', $endpoint, $data);
    }

    /**
    * Send a DELETE request to a Paystack API endpoint.
    *
    * @param string $endpoint
    * @return Response
    *
    * @throws PaystackRequestException
    */
    public function delete(string $endpoint): Response
    {
        return $this->request('delete', $endpoint);
    }
}
