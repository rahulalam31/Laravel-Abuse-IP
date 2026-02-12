![Abuse Ip](https://github.com/user-attachments/assets/ff47c6b4-297f-4984-ae5d-1829b61bd4c6)

# Laravel Abuse-IP

Keep you website safe from spammers.

![Packagist Downloads](https://img.shields.io/packagist/dt/rahulalam31/Laravel-Abuse-IP) ![Packagist Version](https://img.shields.io/packagist/v/rahulalam31/Laravel-Abuse-IP) [![License: MIT](https://img.shields.io/badge/License-MPL%202.0-brightgreen.svg)](https://opensource.org/license/mit) [![Update SPAM IP List](https://github.com/rahulalam31/Laravel-Abuse-IP/actions/workflows/update_spamiplist.yml/badge.svg)](https://github.com/rahulalam31/Laravel-Abuse-IP/actions/workflows/update_spamiplist.yml)

Adds an additional layer of security to Laravel by determining whether an IP address has been reported as spam. This keeps you worry-free by shielding your website from fraudulent submissions and spammers.

This package uses the AbuseIPDB blocklist from [borestad/blocklist-abuseipdb](https://github.com/borestad/blocklist-abuseipdb) by default.

### Installation

1. Run the Composer require command to install the package. The service provider is discovered automatically.

    ```bash
    composer require rahulalam31/laravel-abuse-ip
    ```

2. Publish the configuration file and adapt the configuration as desired:

    ```bash
    php artisan vendor:publish --tag=laravel-abuse-ip
    ```

3. Run the following artisan command to fetch an up-to-date list of disposable domains:

    ```bash
    php artisan abuseip:update
    ```

    - Add the following entries to your `.env` file:
        - Add `ABUSEIP_STORAGE_PATH` to change the storage location.
        - Add `ABUSEIP_STORAGE_COMPRESS` (`true`/`false`) to enable or disable `ip2long()`.

4. (Optional) It's highly advised to update the Abuse-IP list daily, as spam IP addresses change frequently. However, you can schedule updates regularly based on your needs. You may either run the command manually from time to time or, if you use Laravel's scheduler, register the abuseip:update command.

    In `routes/console.php`:

    ```php
    use Illuminate\Support\Facades\Schedule;
    //
    Schedule::command('abuseip:update')->daily();
    ```

    Or if you use Laravel 10 or below, head over to `Http/Kernel.php`:

    ```php
     protected function schedule(Schedule $schedule)
     {
         $schedule->command('abuseip:update')->daily();
     }
    ```

### Usage

Use the `middleware::AbuseIp::class` wherever required, such as on form pages or POST URLs. You can also add the middleware globally to your application. For Laravel 10 and below, register the middleware in `Http/Kernel.php`. For Laravel 11, add it to `bootstrap/app.php`.

```php
//Laravel 10 and below
/*
 * app/Http/Kernel.php
*/

protected $middleware = [
        \RahulAlam31\LaravelAbuseIp\Middleware\AbuseIp::class,
        .....
]


```

```php
//Laravel 11
/*
 * bootstrap/app.php
*/
->withMiddleware(function (Middleware $middleware) {
        $middleware->append(\RahulAlam31\LaravelAbuseIp\Middleware\AbuseIp::class);
    })

```

If you don't want to add it directly to your route middleware, you can create an alias using `aliasMiddleware()` and then use that alias in your routes file to block spam IP visits.

```php
//Laravel 10 and below
/*
 * app/Http/Kernel.php
*/

protected $routeMiddleware = [
        .....,
        'abuseip' => \RahulAlam31\LaravelAbuseIp\Middleware\AbuseIp::class,

]

Route::get('/xyz', function () {
    //
})->middleware('abuseip');
```

```php
//Laravel 11
/*
 * bootstrap/app.php
*/

->withMiddleware(function (Middleware $middleware) {
    //
})
->aliasMiddleware('abuse_ip', \RahulAlam31\LaravelAbuseIp\Middleware\AbuseIp::class)

```

```php
// Or use in route file

Route::middleware(AbuseIp::class)->get('/', function () {
            return view('welcome');
        });

```

### Custom fetches

By default, the package retrieves a new list using `file_get_contents()`.
If you have your own blacklisted IP list, you can add it to the `source` option in `config/abuseip.php`.
