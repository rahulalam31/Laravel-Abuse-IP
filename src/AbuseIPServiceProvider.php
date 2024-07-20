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
            __DIR__.'/../config/abuse-ip.php' => config_path('abuse-ip.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/middleware/AbuseIp.php' => app_path('Http/Middleware/AbuseIp.php'),
        ], 'middleware');

        $this->mergeConfigFrom(
            __DIR__.'/../config/abuse-ip.php', 'abuse-ip'
        );

        if($this->app->runningInConsole()){
            $this->commands([
                UpdateAbuseIps::class,
            ]);
        }

        // $this->app['router']->middleware('block.abuse_ip', AbuseIp::class);


        // Register middleware
    //     $router = $this->app->withMiddleware(function (Middleware $middleware) {
    //         $middleware->append(EnsureTokenIsValid::class);
    //    });
    //     $router->middleware('abuse_ip', AbuseIp::class);

    }

    public function register()
    {
        // $this->app['router']->aliasMiddleware('block.abuse_ip', \RahulAlam31\LaravelAbuseIp\Middleware\AbuseIp::class);
    }

}