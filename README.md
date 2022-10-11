# Network International - Ngenius Payment Gateway Wrapper For Laravel

Jeybin/Ngenius is a wrapper package or in other words a helper package for implementing the Network International (NGENIUS) payment gateway in Laravel Projects.

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
php artisan migrate --path=/database/migrations/ngenius
```

Once all the installation procedures are done there will be two tables available in your database one will be called as `ngenius_gateway` this table holds the configurations for the gateway

| Columns | Description |
| ------ | ------ |
| id | Auto increment value (Primary Key) of the table |
| type | The type of configuration, accepting values are live/sandbox |
| currency | Merchant currency which is configured in the merchant dashboard |
| api_key | Api from network international |
| reference_id | Reference id from Network international |
| base_url | Api base url (different base urls in live and sandbox)|

## Usage

To create purchase order import `use Jeybin\Networkintl\Ngenius`  to the class

```sh
Ngenius::type('create-order')
       ->request($paymentUrlRequest)
       ->execute()
       ->json()
```

The payment request `($paymentUrlRequest)` is an array with following keys.

| Columns | Description |
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



## License

MIT
