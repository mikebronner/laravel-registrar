# Registrar for Laravel
User account activation via email confirmation for Laravel.

## Features
- Account activation through email verification.
- Blocks logins until account is activated.
- Uses Laravel Notifications to send out emails.

## Reasoning
After having set up email activations on a few projects now, it became clear
 that this is something that is going to be used more often, warranting
 extraction to a package for easy reuse with minimal coding.

## Requirements
- PHP 7.0.0+
- Laravel 5.3

## Installation
Install the package using the following command:
```sh
composer require genealabs/laravel-registrar
```

Add the service provider to you app config file `/config/app.php`:
```php
GeneaLabs\LaravelRegistrar\Providers\LaravelRegistrarService::class,
```

Add the following trait to your login controller
 `/app/Http/Controllers/Auth/LoginController.php`:
```php
use ActivatesUsers {
    ActivatesUsers::credentials insteadof AuthenticatesUsers;
}
```
