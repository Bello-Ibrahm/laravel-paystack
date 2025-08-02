<?php

namespace Unicodeveloper\Paystack\Test\Unit;

use Illuminate\Support\Facades\Http;
use Unicodeveloper\Paystack\Client\PaystackClient;
use Unicodeveloper\Paystack\Services\PlanService;
use Unicodeveloper\Paystack\Test\TestCase;

class PlanServiceTest extends TestCase
{
    public function testCreatePlan()
    {
        Http::fake([
            'https://api.paystack.co/plan' => Http::response(['status' => true, 'data' => ['name' => 'Basic Plan']])
        ]);

        $client = new PaystackClient();
        $service = new PlanService($client);
        $response = $service->create(['name' => 'Basic Plan']);

        $this->assertTrue($response['status']);
        $this->assertEquals('Basic Plan', $response['data']['name']);
    }

    public function testListPlans()
    {
        Http::fake([
            'https://api.paystack.co/plan*' => Http::response(['status' => true, 'data' => [['name' => 'Basic Plan']]])
        ]);

        $client = new PaystackClient();
        $service = new PlanService($client);
        $response = $service->list();

        $this->assertTrue($response['status']);
        $this->assertIsArray($response['data']);
    }

    public function testFetchPlan()
    {
        $id = 67890;
        Http::fake([
            "https://api.paystack.co/plan/{$id}" => Http::response(['status' => true, 'data' => ['id' => $id]])
        ]);

        $client = new PaystackClient();
        $service = new PlanService($client);
        $response = $service->fetch($id);

        $this->assertTrue($response['status']);
        $this->assertEquals($id, $response['data']['id']);
    }
}
