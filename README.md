
# Laravel Paysera Integration Package

This Laravel package provides a seamless integration with the Paysera payment gateway, utilizing the official `libwebtopay` library. It includes a variety of helper methods for handling payments, validating responses, and more.

## Installation

To install the package, run the following command in your Laravel project:

```bash
composer require rpagency/laravel-paysera
```

## Configuration

After installing the package, publish the configuration file:

```bash
php artisan vendor:publish --provider="Rpagency\LaravelPaysera\PayseraServiceProvider"
```

This will create a `paysera.php` configuration file in the `config` directory. Update this file with your Paysera project details:

```php
return [
    'project_id' => env('PAYSERA_PROJECT_ID', ''),
    'sign_password' => env('PAYSERA_SIGN_PASSWORD', ''),
    'test_mode' => env('PAYSERA_TEST_MODE', true),
];
```

Ensure you add these environment variables to your `.env` file:

```dotenv
PAYSERA_PROJECT_ID=your_project_id
PAYSERA_SIGN_PASSWORD=your_sign_password
PAYSERA_TEST_MODE=true
```

## Usage

### Generating a Payment URL

To generate a payment URL, you can use the `generatePaymentUrl` method. Here's an example within a Laravel controller:

```php
use Rpagency\LaravelPaysera\Facades\Paysera;

class PaymentController extends Controller
{
    public function createPayment()
    {
        $paymentData = [
            'orderid'       => '123456',
            'amount'        => 1000,  // Amount in cents
            'currency'      => 'EUR',
            'accepturl'     => route('payment.success'),
            'cancelurl'     => route('payment.cancel'),
            'callbackurl'   => route('payment.callback'),
            // Other parameters as needed...
        ];

        try {
            $paymentUrl = Paysera::generatePaymentUrl($paymentData);
            return redirect($paymentUrl);
        } catch (\Exception $e) {
            return back()->withError('Payment could not be initiated: ' . $e->getMessage());
        }
    }
}
```

### Handling Payment Callback

To handle the callback from Paysera after a payment, use the `handlePaymentCallback` method:

```php
use Illuminate\Http\Request;
use Rpagency\LaravelPaysera\Facades\Paysera;

class PaymentController extends Controller
{
    public function handleCallback(Request $request)
    {
        try {
            $response = Paysera::handlePaymentCallback($request);

            // Process the response and update the order status

            return response('OK', 200);
        } catch (\Exception $e) {
            return response('Error', 400);
        }
    }
}
```

### Checking Payment Status

You can check the payment status based on the callback response:

```php
use Rpagency\LaravelPaysera\Facades\Paysera;

$response = Paysera::handlePaymentCallback($request);
$status = Paysera::checkPaymentStatus($response);

if ($status === 'Completed') {
    // Handle completed payment
} elseif ($status === 'Pending') {
    // Handle pending payment
} else {
    // Handle failed payment
}
```

### Validating Signature

To validate the signature of a request or callback, use the `validateSignature` method:

```php
use Rpagency\LaravelPaysera\Facades\Paysera;

$isValid = Paysera::validateSignature($requestData, $signature);

if ($isValid) {
    // Handle valid signature
} else {
    // Handle invalid signature
}
```

### Handling Different Payment Responses

You can also handle different payment responses, such as completed, pending, or failed:

```php
$response = Paysera::handlePaymentCallback($request);
$paymentStatus = Paysera::handlePaymentResponse($response);

switch ($paymentStatus) {
    case 'Payment completed':
        // Handle payment completed
        break;
    case 'Payment pending':
        // Handle payment pending
        break;
    case 'Payment failed':
        // Handle payment failed
        break;
    default:
        // Handle unknown status
        break;
}
```

### Generating a Signed URL

To generate a signed URL for a custom payment request, use the `generateSignedUrl` method:

```php
$signedUrl = Paysera::generateSignedUrl([
    'orderid'       => '123456',
    'amount'        => 1000,  // Amount in cents
    'currency'      => 'EUR',
    // Other parameters...
]);
```

## Additional Information

This package is built on top of the official Paysera `libwebtopay` library, providing a robust and secure integration with Paysera's payment gateway.

### Contributing

Feel free to contribute to this package by submitting a pull request or opening an issue if you find any bugs or have suggestions for improvements.

### License

This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


## Support

If you find this package helpful, consider supporting its development by making a donation:

[![Donate](https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif)](https://www.paypal.com/donate?hosted_button_id=SBH6DZXVL8HC8)
