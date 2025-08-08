<?php

namespace Unicodeveloper\Paystack\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Unicodeveloper\Paystack\Paystack
 *
 * @method static \Unicodeveloper\Paystack\Services\BankService bank()
 * @method static \Unicodeveloper\Paystack\Services\TransactionService transaction()
 * @method static \Unicodeveloper\Paystack\Services\CustomerService customer()
 * @method static \Unicodeveloper\Paystack\Services\PageService page()
 * @method static \Unicodeveloper\Paystack\Services\PlanService plan()
 * @method static \Unicodeveloper\Paystack\Services\SubAccountService subAccount()
 * @method static \Unicodeveloper\Paystack\Services\SubscriptionService subscription()
 * @method static \Unicodeveloper\Paystack\Support\TransRef transRef()
 */
class Paystack extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'laravel-paystack';
    }
}
