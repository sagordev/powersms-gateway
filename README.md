# PowerSMS Gateway PHP Package
The PowerSMS Gateway PHP Package allows you to easily send bulk SMS messages using the [Power SMS](https://www.powersms.net.bd/) service provided by [Banglaphone Limited](https://www.banglaphone.net.bd/). This package is compatible with both Laravel and raw PHP applications, making it versatile for various use cases.

## Installation
You can install this package via Composer:
```
composer require sagordev/powersms-gateway
```

## Laravel Integration
For Laravel integration, follow these additional steps:

Publish the package configuration file:
```
 php artisan vendor:publish --provider="Sagordev\PowersmsGateway\Providers\PowerSmsGatewayServiceProvider"
```
This will publish the configuration file to `config/powersms.php`

Add your Power SMS API credentials and configure other settings in the `config/powersms.php` file:

```php
return [
    'user_id' => 'YOUR_USER_ID',
    'password' => 'YOUR_PASSWORD',
    'url' => '' // You can keep it blank
];
```

## Usage
### Sending SMS

```php
use Sagordev\PowersmsGateway\Facades\PowerSms;

PowerSms::message('This is a test SMS', '01234567890')->send();
// You can also send list of numbers (ex: ['01234567890', '01234567891'])
```

### Sending Same SMS to multiple recipients
```php
PowerSms::message('This is test SMS')
        ->to(['01234567890', '01234567891'])
        ->send();
```

### Add your number(s) in cc to get a carbon copy of message
```php
PowerSms::message('I am SMS with carbon copy to developer')
        ->to(['01234567890', '01234567891'])
        ->cc(['01234567892'])
        ->send();
```

### Another way to send single SMS to multiple recipients
```php
PowerSms::send([
    'message' => 'Hey, This is another SMS',
    'to' => ['01234567890', '01234567891'],
]);
```

## Send Multiple messages to multiple different recipients
```php
PowerSms::send([
      [
          'message' => 'Dear Customer, Your invitation code is #3310',
          'to' => ['01234567890', '01234567891'],
      ],
      [
          'message' => 'Dear Customer, Your invitation code is #0950',
          'to' => '01234567892',
      ]
]);
```

## In your other PHP project
```php
use Sagordev\PowersmsGateway\PowerSms;

$config = [
    'user_id' => 'YOUR_USER_ID',
    'password' => 'YOUR_PASSWORD',
    'url' => '' // You can keep it blank
];

$sms = new PowerSms($config);
$sms->message('Hello', '01234567890')->send();

// Example 2
$sms->message('I am SMS with carbon copy to developer')
        ->to(['01234567890', '01234567891'])
        ->cc(['01234567892'])
        ->send();
// Please look at the previous examples for more fun
```

# Support and Issues
If you encounter any issues or have questions about using this package, please feel free to create an issue on the [GitHub repository](https://github.com/sagordev/banglaphone-powersms-php/issues). I am here to help!

# License
This package is open-sourced software licensed under the MIT License.
