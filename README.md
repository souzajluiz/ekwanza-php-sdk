# e-kwanza PHP SDK

Official Production-Ready PHP SDK for e-kwanza Integrated Payment API and Gateway.
This SDK provides an easy-to-use, PSR-18 compliant wrapper over the e-kwanza ecosystem.

## Features

* **Payment Ticket Generation**: Create payment tickets (Multicaixa / Reference).
* **Ticket Status Checking**: Query the status of your existing tickets.
* **Payment Callback Verification**: Securely validate and parse incoming payment webhooks (HMAC-SHA256).
* **Send to Customer**: Issue direct payments to customers.
* **Gateway Payments (GPO)**: Seamlessly authenticate via Azure AD and create gateway charges.

## Requirements

* PHP 8.2 or higher
* Composer

## Installation

Install using Composer:

```bash
composer require souzajluiz/ekwanza-php-sdk
```

## Basic Configuration

First, you need to instantiate the global configuration.

```php
use Souzajluiz\Ekwanza\Config;
use Souzajluiz\Ekwanza\Enums\Environment;

$config = new Config(
    apiKey: 'your-api-key',
    notificationToken: 'your-notification-token',
    merchantRegistrationNumber: 'your-merchant-reg-number',
    environment: Environment::SANDBOX, // Use Environment::PRODUCTION for live
    
    // Gateway Auth Credentials (optional if only using Tickets)
    clientId: 'gateway-client-id',
    clientSecret: 'gateway-client-secret',
    resource: 'gateway-resource'
);
```

Then create an instance of the `Client`:

```php
use Souzajluiz\Ekwanza\Client;

$ekwanza = new Client($config);
```

## Usage Examples

### 1. Create a Payment Ticket

```php
// Returns a Souzajluiz\Ekwanza\DTO\Ticket object
$ticket = $ekwanza->tickets()->create(
    amount: 1500.50,
    referenceCode: 'ORDER-12345',
    mobileNumber: '+244900000000'
);

echo "Ticket Created! Reference: {$ticket->code}\n";
```

### 2. Check Ticket Status

```php
// Returns a Souzajluiz\Ekwanza\DTO\TicketStatus object
$status = $ekwanza->tickets()->status('TCK-XYZ-123');

if ($status->status === \Souzajluiz\Ekwanza\DTO\TicketStatus::PROCESSED) {
    echo "Payment Completed!";
}
```

### 3. Verify Payment Webhook

e-kwanza sends an `x-signature` header via HMAC-SHA256 to ensure data integrity. The SDK handles this automatically.

```php
use Souzajluiz\Ekwanza\Exceptions\InvalidSignatureException;

$payload = json_decode(file_get_contents('php://input'), true);
$signature = $_SERVER['HTTP_X_SIGNATURE'] ?? '';

try {
    // Returns a Souzajluiz\Ekwanza\DTO\PaymentNotification object
    $notification = $ekwanza->webhooks()->verifyPaymentCallback($payload, $signature);
    
    echo "Valid Webhook received for amount: {$notification->amount}";
    // Proceed to update order status in DB
} catch (InvalidSignatureException $e) {
    http_response_code(401);
    die("Unauthorized: Invalid signature");
}
```

### 4. Send Payment to Customer

```php
$ekwanza->customers()->sendPayment(
    mobileNumber: '+244900000000',
    amount: '500.00',
    operationCode: 'REFUND-001'
);
```

### 5. Gateway Payments (Reference / GPO)

```php
$charge = $ekwanza->gateway()->createCharge(
    tenantId: 'your-azure-tenant-id', // For OAuth Token Generation
    amount: 2500.00,
    merchantTransactionId: 'TRX-999',
    paymentMethod: 'MULTICAIXA', // Or 'GPO'
    description: 'Payment for services'
);

print_r($charge);
```

## Laravel Integration Example

You can easily bind the `Client` into Laravel's service container.

**1. Publish your config (`config/ekwanza.php`)**:

```php
return [
    'api_key' => env('EKWANZA_API_KEY'),
    'notification_token' => env('EKWANZA_NOTIFICATION_TOKEN'),
    'merchant_registration' => env('EKWANZA_MERCHANT_REGISTRATION'),
    'client_id' => env('EKWANZA_CLIENT_ID'),
    'client_secret' => env('EKWANZA_CLIENT_SECRET'),
    'resource' => env('EKWANZA_RESOURCE'),
    'environment' => env('EKWANZA_ENVIRONMENT', 'sandbox'),
];
```

**2. Register the Provider (`App\Providers\EkwanzaServiceProvider`)**:

```php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Souzajluiz\Ekwanza\Config;
use Souzajluiz\Ekwanza\Client;
use Souzajluiz\Ekwanza\Enums\Environment;

class EkwanzaServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(Client::class, function ($app) {
            $envString = config('ekwanza.environment') === 'production' 
                ? Environment::PRODUCTION 
                : Environment::SANDBOX;

            $config = new Config(
                apiKey: config('ekwanza.api_key'),
                notificationToken: config('ekwanza.notification_token'),
                merchantRegistrationNumber: config('ekwanza.merchant_registration'),
                environment: $envString,
                clientId: config('ekwanza.client_id'),
                clientSecret: config('ekwanza.client_secret'),
                resource: config('ekwanza.resource')
            );

            return new Client($config);
        });
    }
}
```

**3. Use it via Dependency Injection**:

```php
use Souzajluiz\Ekwanza\Client;

class CheckoutController extends Controller 
{
    public function process(Client $ekwanza) 
    {
        $ticket = $ekwanza->tickets()->create(1500, 'ORDER_999', '+244900000000');
        // ...
    }
}
```

## Testing

```bash
./vendor/bin/phpunit tests
```

## License

The MIT License (MIT).
