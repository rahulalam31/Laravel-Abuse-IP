<?php

namespace rahulalam31\AbuseIp;

use Illuminate\Support\ServiceProvider;
use rahulalam31\AbuseIp\Console\Commands\UpdateAbuseIps;

class AbuseIpServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/abuse-ip.php' => config_path('abuse-ip.php'),
        ], 'config');

        $this->mergeConfigFrom(
            __DIR__.'/config/abuse-ip.php', 'abuse-ip'
        );

        if($this->app->runningInConsole()){
            $this->commands([
                UpdateAbuseIps::class,
            ]);
        }
    }

    public function register()
    {
        $this->app['router']->aliasMiddleware('block.spam.ip', \rahulalam31\AbuseIp\Middleware\AbuseIp::class);
    }

}