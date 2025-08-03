<?php

namespace Unicodeveloper\Paystack\Test\Integration;

use Illuminate\Support\Str;
use Unicodeveloper\Paystack\Client\PaystackClient;
use Unicodeveloper\Paystack\Test\TestCase;
use Unicodeveloper\Paystack\Services\TransactionService;

class TransactionServiceTest extends TestCase
{
    protected TransactionService $transaction;
    protected string $secretKey;
    protected string $publicKey;
    protected string $paymentUrl;

    protected function setUp(): void
    {
        parent::setUp();

        $this->secretKey = config('paystack.secretKey');
        $this->publicKey = config('paystack.publicKey');
        $this->paymentUrl = config('paystack.paymentUrl');
        
        // $client = new PaystackClient(secretKey: $this->secretKey, baseUrl: $this->paymentUrl);
        // $this->transaction = new TransactionService($client);
        // dump($this->secretKey); 

        $this->transaction = $this->app->make(TransactionService::class);
        
        // Example: Log or use secret key from config
        
    }

    
    
    public function testInitializeTransactionWithRealApi()
    {
        if (! str_starts_with($this->baseUrl, 'http')) {
            throw new \InvalidArgumentException("Invalid Paystack base URL: {$this->baseUrl}");
        }

        $reference = Str::uuid()->toString();
        // dd($this->paymentUrl, $this->secretKey, $this->publicKey,);

        $response = $this->transaction->initialize([
            'email' => 'customer@example.com',
            'amount' => 5000, // in kobo
            'reference' => $reference,
            'callback_url' => 'https://example.com/callback'
        ]);

        $this->assertIsArray($response);
        $this->assertTrue($response['status']);
        $this->assertArrayHasKey('authorization_url', $response['data']);
        $this->assertArrayHasKey('reference', $response['data']);
    }

    public function testVerifyTransactionWithRealApi()
    {
        $reference = Str::uuid()->toString();

        $initResponse = $this->transaction->initialize([
            'email' => 'verify@example.com',
            'amount' => 10000,
            'reference' => $reference,
            'callback_url' => 'https://example.com/callback'
        ]);

        $this->assertTrue($initResponse['status']);

        // Simulate verifying the same reference
        $verifyResponse = $this->transaction->verify($reference);

        $this->assertIsArray($verifyResponse);
        $this->assertTrue($verifyResponse['status']);
        $this->assertEquals($reference, $verifyResponse['data']['reference']);
    }

    public function testListTransactions()
    {
        $response = $this->transaction->list(perPage: 10, page: 1);
        
        // dd(gettype($response));

        $this->assertIsArray($response);
        $this->assertTrue($response['status']);
        $this->assertArrayHasKey('data', $response);
        $this->assertIsArray($response['data']);
    }

    public function testFetchTransactionById()
    {
        $listResponse = $this->transaction->list(perPage: 1);

        $this->assertTrue($listResponse['status']);
        $transactions = $listResponse['data'];

        if (count($transactions) > 0) {
            $id = $transactions[0]['id'];

            $fetchResponse = $this->transaction->fetch($id);

            $this->assertTrue($fetchResponse['status']);
            $this->assertEquals($id, $fetchResponse['data']['id']);
        } else {
            $this->markTestSkipped('No transactions available to fetch.');
        }
    }

}
