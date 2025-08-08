# laravel-paystack

[![Latest Stable Version](https://poser.pugx.org/unicodeveloper/laravel-paystack/v/stable.svg)](https://packagist.org/packages/unicodeveloper/laravel-paystack)
[![License](https://poser.pugx.org/unicodeveloper/laravel-paystack/license.svg)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/unicodeveloper/laravel-paystack.svg)](https://travis-ci.org/unicodeveloper/laravel-paystack)
[![Quality Score](https://img.shields.io/scrutinizer/g/unicodeveloper/laravel-paystack.svg?style=flat-square)](https://scrutinizer-ci.com/g/unicodeveloper/laravel-paystack)
[![Total Downloads](https://img.shields.io/packagist/dt/unicodeveloper/laravel-paystack.svg?style=flat-square)](https://packagist.org/packages/unicodeveloper/laravel-paystack)

> A Laravel Package for working with Paystack seamlessly

## Installation

[PHP](https://php.net) 5.4+ or [HHVM](http://hhvm.com) 3.3+, and [Composer](https://getcomposer.org) are required.

To get the latest version of Laravel Paystack, simply require it

```bash
composer require unicodeveloper/laravel-paystack:2.0.0
```

Or add the following line to the require block of your `composer.json` file.

```
"unicodeveloper/laravel-paystack": "2.0.*"
```

You'll then need to run `composer install` or `composer update` to download it and have the autoloader updated.



Once Laravel Paystack is installed, you need to register the service provider. Open up `config/app.php` and add the following to the `providers` key.

```php
'providers' => [
    ...
    Unicodeveloper\Paystack\PaystackServiceProvider::class,
    ...
]
```

> If you use **Laravel >= 5.5** you can skip this step and go to [**`configuration`**](https://github.com/unicodeveloper/laravel-paystack#configuration)

* `Unicodeveloper\Paystack\PaystackServiceProvider::class`

Also, register the Facade like so:

```php
'aliases' => [
    ...
    'Paystack' => Unicodeveloper\Paystack\Facades\Paystack::class,
    ...
]
```

## Configuration

You can publish the configuration file using this command:

```bash
php artisan vendor:publish --provider="Unicodeveloper\Paystack\PaystackServiceProvider"
```

A configuration-file named `paystack.php` with some sensible defaults will be placed in your `config` directory:

```php
<?php

return [

    /**
     * Public Key From Paystack Dashboard
     *
     */
    'publicKey' => env('PAYSTACK_PUBLIC_KEY'),

    /**
     * Secret Key From Paystack Dashboard
     *
     */
    'secretKey' => env('PAYSTACK_SECRET_KEY'),

    /**
     * Paystack Payment URL
     *
     */
    'paymentUrl' => env('PAYSTACK_PAYMENT_URL'),

    /**
     * Optional email address of the merchant
     *
     */
    'merchantEmail' => env('MERCHANT_EMAIL'),

    // Maximum retry attempts for HTTP client (default: 3)
    'retry_attempts' => env('PAYSTACK_RETRY_ATTEMPTS', 3),

    // Delay (ms) between retry attempts (default: 150)
    'retry_delay' => env('PAYSTACK_RETRY_DELAY', 150),

    /*
    |--------------------------------------------------------------------------
    | Enable Package Routes - Feature
    |--------------------------------------------------------------------------
    |
    | This option controls whether the Paystack package should automatically
    | load its built-in web routes. You may disable this if you prefer
    | to define your own routes or extend the functionality manually.
    |
    | Default: false
    |
    */
    'enable_routes' => false,

];
```


## General payment flow

Though there are multiple ways to pay an order, most payment gateways expect you to follow the following flow in your checkout process:

### 1. The customer is redirected to the payment provider
After the customer has gone through the checkout process and is ready to pay, the customer must be redirected to the site of the payment provider.

The redirection is accomplished by submitting a form with some hidden fields. The form must send a POST request to the site of the payment provider. The hidden fields minimally specify the amount that must be paid, the order id and a hash.

The hash is calculated using the hidden form fields and a non-public secret. The hash used by the payment provider to verify if the request is valid.


### 2. The customer pays on the site of the payment provider
The customer arrives on the site of the payment provider and gets to choose a payment method. All steps necessary to pay the order are taken care of by the payment provider.

### 3. The customer gets redirected back to your site
After having paid the order the customer is redirected back. In the redirection request to the shop-site some values are returned. The values are usually the order id, a payment result and a hash.

The hash is calculated out of some of the fields returned and a secret non-public value. This hash is used to verify if the request is valid and comes from the payment provider. It is paramount that this hash is thoroughly checked.


## Usage

Open your .env file and add your public key, secret key, merchant email and payment url like so:

```php
PAYSTACK_PUBLIC_KEY=xxxxxxxxxxxxx
PAYSTACK_SECRET_KEY=xxxxxxxxxxxxx
PAYSTACK_PAYMENT_URL=https://api.paystack.co
MERCHANT_EMAIL=unicodeveloper@gmail.com
```
*If you are using a hosting service like heroku, ensure to add the above details to your configuration variables.*

Set up routes and controller methods like so:

Note: Make sure you have `/payment/callback` registered in Paystack Dashboard [https://dashboard.paystack.co/#/settings/developer](https://dashboard.paystack.co/#/settings/developer) like so:

![payment-callback](https://cloud.githubusercontent.com/assets/2946769/12746754/9bd383fc-c9a0-11e5-94f1-64433fc6a965.png)

## Route Example
```
Route::get('/payment', [App\Http\Controllers\PaymentController::class, 'index'])->name('payment.form');
Route::post('/checkout', [App\Http\Controllers\PaymentController::class, 'redirectToGateway'])->name('checkout.process');
Route::get('/payment/callback', [App\Http\Controllers\PaymentController::class, 'handleGatewayCallback'])->name('payment.callback');
```

## Controller Example:

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Unicodeveloper\Paystack\Facades\Paystack;

/**
 * Class PaymentController
 *
 * Handles Paystack payment initialization, redirection, and callback verification.
 *
 * @package App\Http\Controllers
 */
class PaymentController extends Controller
{
    /**
     * Display the payment form to the user.
     *
     * @return \Illuminate\View\View
    */
    public function index()
    {
        return view('payments.index');
    }

    /**
     * Initialize a Paystack transaction and redirect to the authorization URL.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
    */
    public function redirectToGateway(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'amount' => 'required|numeric|min:100',
            'description' => 'nullable|string',
        ]);

        $reference = Paystack::transRef();

        $payload = [
            'email' => $request->email,
            'amount' => $request->amount * 100,
            'reference' => $reference,
            'callback_url' => route('payment.callback'),
            'metadata' => [
                'custom_fields' => [
                    [
                        'display_name' => 'Name',
                        'variable_name' => 'name',
                        'value' => $request->name
                    ],
                    [
                        'display_name' => 'Description',
                        'variable_name' => 'description',
                        'value' => $request->description
                    ]
                ]
            ]
        ];

        try {
            $response = Paystack::transaction()->initialize($payload);
            $transAuthURL = $response['data']['authorization_url']; 
            
            return redirect($transAuthURL);
        } catch (\Exception $e) {
            Log::error('Paystack Error', ['message' => $e->getMessage()]);
            return back()->with('error', 'Failed to initiate payment.');
        }
    }

    /**
     * Handle the callback from Paystack after payment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function handleGatewayCallback(Request $request)
    {
        $reference = $request->query('reference');

        try {
            $response = Paystack::transaction()->verify($reference);
            $data = $response['data'];

            // Here, you could store the payment record, send a receipt email, etc.
            return view('payments.success', ['payment' => $data]);
        } catch (\Exception $e) {
            Log::error('Verification Error', ['message' => $e->getMessage()]);
            return redirect('/payment')->with('error', 'Payment verification failed.');
        }
    }
}
```
## View example `views/payments/payment.blade.php`:

```php
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card my-5">
        <div class="card-header">
            <h3 class="mb-4 text-center">Pay with Paystack</h3>
        </div>

        <div class="card-body">
            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('checkout.process') }}">
                @csrf

                <div class="form-group mb-3">
                    <label>Name</label>
                    <input type="text" class="form-control" name="name" required>
                </div>

                <div class="form-group mb-3">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>

                <div class="form-group mb-3">
                    <label>Amount (NGN)</label>
                    <input type="number" class="form-control" name="amount" min="100" required>
                </div>

                <div class="form-group mb-3">
                    <label>Description</label>
                    <input type="text" class="form-control" name="description" value="Laravel Blog Premium" required>
                </div>

                <button type="submit" class="btn btn-success w-100">Pay Now</button>
            </form>
        </div>
    </div>
</div>
@endsection
```

```php
/**
 *  In the case where you need to pass the data from your 
 *  controller instead of a form
 *  Make sure to send:
 *  required: email, amount, reference, orderID(probably)
 *  optionally: currency, description, metadata
 *  e.g:
 *  
 */
$data = array(
        "amount" => 700 * 100,
        "reference" => '4g4g5485g8545jg8gj',
        "email" => 'user@mail.com',
        "currency" => "NGN",
        "orderID" => 23456,
    );

$response = Paystack::transaction()->initialize($data);

return redirect($response['data']['authorization_url']);
```

Let me explain the fluent methods this package provides a bit here.
```php
/**
 * Initialize a new transaction for a customer.
 *
 * @param array $data {
 *     @type string $email         Customer's email address (required).
 *     @type int    $amount        Amount in kobo (e.g. 5000 = ₦50.00) (required).
 *     @type string $reference     Unique transaction reference (optional - auto-generated if omitted).
 *     @type string $callback_url  URL to redirect to after payment (optional).
 *     @type array  $metadata      Custom metadata including custom_fields (optional).
 * }
 * @return array Response from Paystack API.
 */
Paystack::transaction()->initialize(array $data);

/**
 * Verify the status of a transaction using its reference.
 *
 * @param string $ref Unique transaction reference to verify.
 * @return array Response from Paystack API containing transaction details.
 */
Paystack::transaction()->verify(string $ref);

/**
 * Fetch details of a single transaction by its ID or reference.
 *
 * @param string $id_or_ref Optional transaction ID or reference.
 * @return array Response with transaction details.
 */
Paystack::transaction()->fetch(string $id_or_ref);

/**
 * List all transactions for the authenticated Paystack account.
 *
 * @return array Paginated list of transactions.
 */
Paystack::transaction()->list();

/**
 * Charge a customer using a saved authorization code.
 *
 * @param array $data {
 *     @type string $authorization_code  The saved Paystack authorization code (required).
 *     @type string $email               Customer's email (required).
 *     @type int    $amount              Amount in kobo (required).
 *     @type string $reference           Unique reference (optional).
 * }
 * @return array Response from Paystack API.
 */
Paystack::transaction()->chargeAuthorization(array $data);

/**
 * Create a new customer on Paystack.
 *
 * @param array $data {
 *     @type string $email     Customer's email address (required).
 *     @type string $first_name First name of the customer (optional).
 *     @type string $last_name  Last name of the customer (optional).
 *     @type string $phone      Customer's phone number (optional).
 * }
 * @return array Response with created customer details.
 */
Paystack::customer()->create(array $data);

/**
 * Fetch a customer's details using email or customer code.
 *
 * @param string $email_or_code Email address or customer code.
 * @return array Customer details from Paystack API.
 */
Paystack::customer()->fetch(string $email_or_code);

/**
 * List all customers on your Paystack account.
 *
 * @return array Paginated list of customers.
 */
Paystack::customer()->list();

/**
 * Generate a unique transaction reference string.
 *
 * @return string Unique transaction reference.
 */
Paystack::transRef();

```


When clicking the submit button the customer gets redirected to the Paystack site.

So now we've redirected the customer to Paystack. The customer did some actions there (hopefully he or she paid the order) and now gets redirected back to our shop site.

Paystack will redirect the customer to the url of the route that is specified in the Callback URL of the Web Hooks section on Paystack dashboard.

You can test with these details

```bash
Card Number: 4123450131001381
Expiry Date: any date in the future
CVV: 883
```

## Todo

* Charge Returning Customers
* Add Comprehensive Tests
* Implement Transaction Dashboard to see all of the transactions in your laravel app

## Contributing

Please feel free to fork this package and contribute by submitting a pull request to enhance the functionalities.

## How can I thank you?

Why not star the github repo? I'd love the attention! Why not share the link for this repository on Twitter or HackerNews? Spread the word!

Don't forget to [follow me on twitter](https://twitter.com/unicodeveloper)!

Thanks!
Prosper Otemuyiwa.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
