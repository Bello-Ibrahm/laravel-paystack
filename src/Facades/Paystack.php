<?php

namespace Unicodeveloper\Paystack\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Unicodeveloper\Paystack\Paystack
 *
 * @method static \Unicodeveloper\Paystack\Services\TransactionService transaction()
 * @method static \Unicodeveloper\Paystack\Services\CustomerService customer()
 * @method static \Unicodeveloper\Paystack\Services\SubscriptionService subscription()
 * @method static \Unicodeveloper\Paystack\Services\PlanService plan()
 * @method static \Unicodeveloper\Paystack\Services\TransferService transfer()
 * @method static \Unicodeveloper\Paystack\Services\TransferRecipientService transferRecipient()
 * @method static \Unicodeveloper\Paystack\Services\PaymentPageService paymentPage()
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
