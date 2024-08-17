<?php

namespace RahulAlam31\LaravelAbuseIp\tests\Feature;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase;
use RahulAlam31\LaravelAbuseIp\AbuseIPServiceProvider;
use RahulAlam31\LaravelAbuseIp\Middleware\AbuseIp;

class BlockIPTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [AbuseIPServiceProvider::class];
    }

    protected function setUp(): void
    {
        parent::setUp();

        // Clear cache before running tests
        Cache::flush();

        Route::middleware(AbuseIp::class)->get('/test-route', function () {
            return 'ok';
        });

        // Ensure the configuration is loaded
        $this->app['config']->set('abuse-ip', include __DIR__.'/../../config/abuseip.php');
    }

    public function testRequestFromBlockedIp()
    {

        // Add test IPs to the cache
        // Cache::forever('abuse_ips', ['192.168.0.1', '10.0.0.1']);

        // Simulate request from a blocked IP
        $response = $this->withServerVariables(['REMOTE_ADDR' => '1.0.136.77'])->get('/test-route');
        $response->assertStatus(403);
        $response->assertSee('Your IP address has been blocked');
    }

    public function testRequestFromAllowedIp()
    {
        // Add test IPs to the cache
        Cache::forever('abuse_ips', ['192.168.0.1', '10.0.0.1']);

        // Simulate request from a non-blocked IP
        $response = $this->withServerVariables(['REMOTE_ADDR' => '8.8.8.8'])->get('/test-route');
        $response->assertStatus(200);
        $response->assertSee('ok');
    }
}
