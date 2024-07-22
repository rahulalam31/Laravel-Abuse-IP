<?php

namespace RahulAlam31\LaravelAbuseIp;

use Illuminate\Support\ServiceProvider;
use RahulAlam31\LaravelAbuseIp\Console\Commands\UpdateAbuseIps;
use RahulAlam31\LaravelAbuseIp\Middleware\AbuseIp;

class AbuseIPServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/abuseip.php' => config_path('abuseip.php'),
        ], 'laravel-abuse-ip');

        $this->publishes([
            __DIR__.'/Middleware/AbuseIp.php' => app_path('Http/Middleware/AbuseIp.php'),
        ], 'laravel-abuse-ip');

        $this->mergeConfigFrom(
            __DIR__.'/../config/abuseip.php', 'abuseip'
        );
        
        if($this->app->runningInConsole()){
            $this->commands([
                UpdateAbuseIps::class,
            ]);
        }

        $this->app['router']->middleware('abuse_ip', AbuseIp::class);


        // Register middleware
    //     $router = $this->app->withMiddleware(function (Middleware $middleware) {
    //         $middleware->append(EnsureTokenIsValid::class);
    //    });
    //     $router->middleware('abuse_ip', AbuseIp::class);

    }

    public function register()
    {

        // $this->mergeConfigFrom(
        //     __DIR__.'/../config/abuseip.php', 'abuseip'
        // );
        // $this->app['router']->aliasMiddleware('block.abuse_ip', \RahulAlam31\LaravelAbuseIp\Middleware\AbuseIp::class);
    }

}