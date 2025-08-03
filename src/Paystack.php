<?php

/*
 * This file is part of the Laravel Paystack package.
 *
 * (c) Prosper Otemuyiwa <prosperotemuyiwa@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Unicodeveloper\Paystack;

use Unicodeveloper\Paystack\Services\TransactionService;
use Unicodeveloper\Paystack\Services\CustomerService;
use Unicodeveloper\Paystack\Services\PlanService;
use Unicodeveloper\Paystack\Services\SubscriptionService;
use Unicodeveloper\Paystack\Services\PageService;
use Unicodeveloper\Paystack\Services\SubAccountService;
use Unicodeveloper\Paystack\Services\BankService;
use Unicodeveloper\Paystack\Client\PaystackClient;
use Unicodeveloper\Paystack\Support\TransRef;

/**
 * Paystack Service Container
 *
 * Provides access to Paystack services like Transaction, Customer, Plan, etc.
 * @package Unicodeveloper\Paystack
*/
class Paystack
{
    protected PaystackClient $client;

    /**
     * Create a new Paystack instance.
     *
     * @param  PaystackClient  $client
    */
    public function __construct(PaystackClient $client)
    {
        $this->client = $client;
    }

    /**
     * Get the TransactionService instance.
     *
     * @return TransactionService
    */
    public function transaction(): TransactionService
    {
        return new TransactionService($this->client);
    }

    /**
     * Get the CustomerService instance.
     *
     * @return CustomerService
    */
    public function customer(): CustomerService
    {
        return new CustomerService($this->client);
    }

    /**
     * Get the PlanService instance.
     *
     * @return PlanService
    */
    public function plan(): PlanService
    {
        return new PlanService($this->client);
    }

    /**
     * Get the SubscriptionService instance.
     *
     * @return SubscriptionService
    */
    public function subscription(): SubscriptionService
    {
        return new SubscriptionService($this->client);
    }

    /**
     * Get the PageService instance.
     *
     * @return PageService
    */
    public function page(): PageService
    {
        return new PageService($this->client);
    }

    /**
     * Get the SubAccountService instance.
     *
     * @return SubAccountService
    */
    public function subAccount(): SubAccountService
    {
        return new SubAccountService($this->client);
    }

    /**
     * Get the BankService instance.
     *
     * @return BankService
    */
    public function bank(): BankService
    {
        return new BankService($this->client);
    }

    /**
     * Generate a unique transaction reference.
     *
     * @return string
    */
    public function transRef(): string
    {
        return TransRef::generate();
    }
}
