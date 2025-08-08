<?php

use Unicodeveloper\Paystack\Paystack;

/**
 * Get the Paystack service instance.
 *
 * This helper provides easy access to the Paystack package's main interface.
 *
 * Example:
 * ```php
 * $paystack = paystack();
 * $response = $paystack->transaction()->initialize($payload);
 * ```
 *
 * @return \Unicodeveloper\Paystack\Paystack
*/
if (! function_exists("paystack")) {
    function paystack(): Paystack
    {
        return app()->make('laravel-paystack');
    }
}
