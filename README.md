# Registrar for Laravel
[![Join the chat at https://gitter.im/GeneaLabs/laravel-registrar](https://badges.gitter.im/GeneaLabs/laravel-registrar.svg)](https://gitter.im/GeneaLabs/laravel-registrar?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

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
use GeneaLabs\LaravelRegistrar\Traits\ActivatesUsers;

//class LoginController extends Controller
//{
    use ActivatesUsers {
        ActivatesUsers::attemptLogin insteadof AuthenticatesUsers;
    }
```

And finally, add this trait to your User model:
```php
use GeneaLabs\LaravelRegistrar\Traits\Activatable;

//class User
//{
 use Activatable;
```

## Usage
Each time a new user is created, an activation token will be added to their
 record and an email activation notification sent out with a link that will
 activate their user account by removing the activation token and setting the
 activation timestamp. The user will then be able to log into their account.

### Conditional Activation Notices
To send out the notification email only when a certain condition is met,
override the `getCanBeActivatedAttribute()` method in your `User` class. By
default this method returns true, unless you override it.
```php
public function getCanBeActivatedAttribute() : bool
{
    // return true or false based on your specific condition.
}
```

### Customization
You can customize the notification email by implementing your own Notification
class, then overriding the following method in your User class:
```php
protected static function sendNotification()
{
    static::created(function ($user) {
        $user->notify(new MyOwnNotificationClass($user));
    });
}
```

To alter the notification template itself, follow the steps outlined in the
 Laravel documentation: https://laravel.com/docs/5.3/notifications#customizing-the-templates.
