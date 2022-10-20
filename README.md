
# Network International - Ngenius Payment Gateway Wrapper For Laravel

jeybin/networkintl is a wrapper package or in other words a helper package for implementing the Network International (NGENIUS) payment gateway in Laravel Projects.

## Features

- Easier create order 
- Webhook helpers

## Installation

Requires PHP v7.0+ to run.
Install the jeybin/ngenius using the command

```sh
composer require jeybin/ngenius
```
After installation publish the config and service providers using
```sh
php artisan ngenius:install
```
Once everything publishes, run the migration command to create the required tables
```sh
php artisan ngenius:migrate
```
If you want to copy the Job files from the package run the command below, this will create the Jobs files required for the webhook listener.
 (path : App\Jobs\NgeniusWebhooks)
```sh
php artisan ngenius:ngenius-webhooks
```


Once all the installation procedures are done there will be two tables available in your database one will be called as `ngenius_gateway` this table holds the configurations for the gateway

| Columns | Description |
| ------ | ------ |
| id | Auto increment value (Primary Key) of the table |
| active | 1 or 0 for assigning the active gateway if have multiple |
| type | The type of configuration, accepting values are live/sandbox |
| api_key | Api from network international |
| reference_id | Reference id from Network international |
| base_url | Api base url (different base urls in live and sandbox)|

Other table is `ngenius_gateway_webhooks` this table will save the data received in the webhook URL. 
| Columns | Description |
| ------ | ------ |
| id | Auto increment value (Primary Key) of the table |
| event_id | Event id (payload->eventId)|
| event_name | Event name  (payload->eventName)|
| order_reference | Order reference (payload->order->reference)|
| merchant_order_reference | Merchant order reference (payload->order->merchantOrderReference)|
| email | Email of the payer (payload->order->emailAddress)|
| currency | Payment currency (payload->order->amount->currencyCode)|
| amount | Payment amount  (payload->order->amount->amount)|
| payload | Full payload data received inside the webhook url in JSON format (payload)|
| exception | Save exception if any |


## Usage

To create purchase order import `use Jeybin\Networkintl\Ngenius`  to the class

```sh
Ngenius::type('create-order')
       ->request($paymentUrlRequest)
       ->execute()
       ->json()
```

The payment request `($paymentUrlRequest)` is an array with following keys.

| Key | Description |
| ------ | ------ |
| amount | Payment amount in merchant currency |
| payer_email | Email address of the payer |
| order_reference | Merchant order reference number |
| redirect_url | Redirection path once the payment is successed  |
| cancel_url | Redirection path once the payment is cancelled |
| cancel_text | The text need to be shown in the cancel button |
| merchant_defined | Array of details you want like to pass like product name etc|
| language | Gateway language, default is English, allowed parameters `en`,`ar` and `fr` |
| billing | Required, Array of billing array details parameters are `first_name`, `last_name` , `address`,`city`,`country`|
| skip_confirmation_page | Boolean value, by default `false` |
| skip3DS | Boolean value, by default `false` |

## Reference

 - [Laravel Official Documentation](https://laravel.com/docs/9.x/installation)
 - [Laravel Http Client](https://laravel.com/docs/9.x/http-client) 
 - [Laravel Jobs](https://laravel.com/docs/9.x/queues#creating-jobs) 
 - [Network International Payment Gateway Documentation](https://docs.ngenius-payments.com)
 - [Network International Payment Gateway Webhooks](https://docs.ngenius-payments.com/reference/consuming-web-hooks)

## License

MIT
