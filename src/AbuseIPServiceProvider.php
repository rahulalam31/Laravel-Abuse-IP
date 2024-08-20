<?php

namespace RahulAlam31\LaravelAbuseIp;

use Illuminate\Support\ServiceProvider;
use RahulAlam31\LaravelAbuseIp\Console\Commands\UpdateAbuseIps;
use RahulAlam31\Middleware\AbuseIp;

class AbuseIPServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/abuseip.php' => config_path('abuseip.php'),
        ], 'laravel-abuse-ip');

        $this->publishes([
            __DIR__.'/../abuseip.json' => storage_path('framework/abuseip.json'),
        ], 'laravel-abuse-ip');

        $this->mergeConfigFrom(
            __DIR__.'/../config/abuseip.php', 'abuseip'
        );

        if($this->app->runningInConsole()){
            $this->commands([
                UpdateAbuseIps::class,
            ]);
        }

        $this->app['router']->aliasMiddleware('abuse_ip', AbuseIp::class);


    }

    public function register()
    {

        // $this->mergeConfigFrom(
        //     __DIR__.'/../config/abuseip.php', 'abuseip'
        // );
        // $this->app['router']->aliasMiddleware('block.abuse_ip', \RApp\Http\Middleware\AbuseIp::class);
    }

}
