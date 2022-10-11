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

## Usage

Once all the installation procedures are done there will be two tables available in your database one will be called as `ngenius_gateway` this table holds the configurations for the gateway

| Columns | Description |
| ------ | ------ |
| id | Auto increment value (Primary Key) of the table |
| type | The type of configuration, accepting values are live/sandbox |
| currency | Merchant currency which is configured in the merchant dashboard |
| api_key | Api from network international |
| reference_id | Reference id from Network international |
| base_url | Api base url (different base urls in live and sandbox)|


## License

MIT
