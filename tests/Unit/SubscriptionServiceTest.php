<?php

namespace Unicodeveloper\Paystack\Test\Unit;

use Illuminate\Support\Facades\Http;
use Unicodeveloper\Paystack\Client\PaystackClient;
use Unicodeveloper\Paystack\Facades\Paystack;
use Unicodeveloper\Paystack\Services\SubscriptionService;
use Unicodeveloper\Paystack\Test\TestCase;

class SubscriptionServiceTest extends TestCase
{
    public function testCreateSubscription()
    {
        Http::fake([
            'https://api.paystack.co/subscription' => Http::response(['status' => true, 'data' => ['email' => 'user@example.com']])
        ]);

        $response = Paystack::subscription()->create(
            [
                'email' => 'user@example.com'
            ]
        );
        
        $this->assertTrue($response['status']);
        $this->assertEquals('user@example.com', $response['data']['email']);
    }

    public function testDisableSubscription()
    {
        Http::fake([
            'https://api.paystack.co/subscription/disable' => Http::response(['status' => true])
        ]);

        $response = Paystack::subscription()->disable(['code' => 'SUB123']);

        $this->assertTrue($response['status']);
    }

    public function testEnableSubscription()
    {
        Http::fake([
            'https://api.paystack.co/subscription/enable' => Http::response(['status' => true])
        ]);

        $client = new PaystackClient();
        $service = new SubscriptionService($client);
        $response = $service->enable(['code' => 'SUB123']);

        $this->assertTrue($response['status']);
    }

    public function testFetchSubscription()
    {
        $code = 'SUB123';
        Http::fake([
            "https://api.paystack.co/subscription/{$code}" => Http::response(['status' => true, 'data' => ['subscription_code' => $code]])
        ]);

        // $client = new PaystackClient();
        // $service = new SubscriptionService($client);
        $response = paystack()->subscription()->fetch($code);

        $this->assertTrue($response['status']);
        $this->assertEquals($code, $response['data']['subscription_code']);
    }
}
