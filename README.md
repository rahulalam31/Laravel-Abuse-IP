<div align="center">
![Untitled design(1)(1)](https://github.com/user-attachments/assets/a776c6b6-e08c-4e80-ab80-78e6142a12f0)
</div>
# Laravel Abuse-IP

##Keep you webiste safe from spammer.

![Packagist Downloads](https://img.shields.io/packagist/dt/rahulalam31/Laravel-Abuse-IP) ![Packagist Version](https://img.shields.io/packagist/v/rahulalam31/Laravel-Abuse-IP) [![License: MIT](https://img.shields.io/badge/License-MPL%202.0-brightgreen.svg)](https://opensource.org/license/mit) ![PHP](https://img.shields.io/badge/python-3.6-blue.svg)


Adds a Security to Laravel for checking whether the IP address is safe or marked as spam to keep you out of worry from spammers and fake data to your website. 
Uses the AbuseIPDB blocklist from [borestad/blocklist-abuseipdb](https://github.com/borestad/blocklist-abuseipdb) by default.

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


4. (optional) It's highly advised to update the AbuseIp list daily as the spam ip address keeps changing daily, but you can schedule it as per your need regularly. You can either run the command yourself now and then or, if you make use of Laravel's scheduler, you can register the `abuseip:update` command: 

   In `routes/console.php`:
    ```php
    use Illuminate\Support\Facades\Schedule;
    // 
    Schedule::command('abuseip:update')->daily();
    ```

    Or if you use Laravel 10 or below, head over to the Console kernel:
   ```php
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('abuseip:update')->daily();
    }
    ```
### Usage

Use the `middleware::AbuseIp::class` where ever required like in form page or post urls.Or you  can add the middleware to your code, For Laravel 10 and below add the middleware `Http/Kernel.php`, For Laravel 11 add to `bootstrap/app/php`

```php
//Laravel 10 and below
/*
 * app/Http/Kernel.php
*/

protected $middleware = [
        \App\Http\Middleware\AbuseIp::class,
        .....
]


```


```php
//Laravel 11
/*
 * bootstrap/app.php
*/
->withMiddleware(function (Middleware $middleware) {
        $middleware->append(\App\Http\Middleware\AbuseIp::class);
    })

```


```php
// Or use in route file

Route::middleware(AbuseIp::class)->get('/', function () {
            return view('welcome');
        });

```

### Custom fetches

By default the package retrieves a new list by using `file_get_contents()`.
If you have your own blacklisted Ip List add it to the `source` in `config/abuseip.php`
