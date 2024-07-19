<?php

namespace RahulAlam31\LaravelAbuseIp\tests\Feature;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;
use RahulAlam31\LaravelAbuseIp\AbuseIPServiceProvider;

class UpdateAbuseIpTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [AbuseIpServiceProvider::class];
    }

    public function testUpdateAbuseIpCommand()
    {
        Http::fake([
            'https://raw.githubusercontent.com/borestad/blocklist-abuseipdb/master/ips.txt' => Http::response("192.168.0.1\n10.0.0.1\n", 200),
            'https://example.com/another-ip-list.txt' => Http::response("172.16.0.1\n10.0.0.1\n127.0.0.1", 200),

        ]);

        //Run the Console command
        Artisan::call('abuseip:update');

        // Assert the command output
        $this->assertStringContainsString('IP blocklist updated successfully', Artisan::output());

        // Assert the cache contains the correct IPS
        $cachedIps =Cache::get('abuse_ips');
        $this->assertNotEmpty($cachedIps);
        $this->assertContains('1.0.136.77', $cachedIps);
        $this->assertContains('1.0.158.159', $cachedIps);
        $this->assertContains('1.1.109.151', $cachedIps);

        // Check that ip.json file is updated
        $ipjsonPath = config('abuse-ip.storage') ;
        $this->assertFileExists($ipjsonPath);
        $ipsfromFile = json_decode(file_get_contents($ipjsonPath), true);
        $this->assertNotEmpty($ipsfromFile);
        $this->assertContains('1.0.136.77', $ipsfromFile);
        $this->assertContains('1.0.158.159', $ipsfromFile);
        $this->assertContains('1.1.109.151', $ipsfromFile);

    }
}
