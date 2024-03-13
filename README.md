# Laravel Rest API Authentication Using JWT Tutorial
## A Sandbox Try and Fail Project

This project produce API for login and register user using Laravel 10

Tech stack:
- Laravel 10
- MySQL

Sauce: https://fajarwz.com/blog/laravel-rest-api-authentication-using-jwt-tutorial/

### Custom Error Handling / Error Handler / Handle Error
Sauce: https://imansugirman.com/laravel-custom-error-handler-json-response

Custom error handler for unauthorized user (error unauthenticated)
Sauce: https://laracasts.com/discuss/channels/laravel/custom-message-for-unauthorized-api-call-in-laravel-8?page=1&replyId=718394


## Branch
- master
- feature/custom-error-handling-byimansugirman

## How to Run Application

```bash
php artisan migrate
php artisan serve
```

Tested on Firefox

## Testing

Unit Test with PHPUnit

```bash
./vendor/bin/phpunit tests/Feature/Http/Controllers/Api/UserControllerTest.php
```

References:
- https://perogeremmer.medium.com/bermain-dengan-php-unit-test-di-laravel-part-1-d67771795733

- https://laravel.com/docs/10.x/http-tests#assert-json-validation-errors

- https://semaphoreci.com/community/tutorials/getting-started-with-phpunit-in-laravel