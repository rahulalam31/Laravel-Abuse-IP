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
        config([
            'abuseip.source' => [
                'https://raw.githubusercontent.com/borestad/blocklist-abuseipdb/main/abuseipdb-s100-14d.ipv4',
                'https://example.com/blocklist-with-comments.txt',
            ],
            'abuseip.storage.compress' => false,
        ]);

        Http::fake([
            'https://raw.githubusercontent.com/borestad/blocklist-abuseipdb/main/abuseipdb-s100-14d.ipv4' => Http::response("192.168.0.1\n10.0.0.1\n"),
            'https://example.com/blocklist-with-comments.txt' => Http::response("# Some Blocklist\n1.2.3.4 # brute-force\n5.6.7.8\n127.0.0.9"),
        ]);

        //Run the Console command
        Artisan::call('abuseip:update');

        // Assert the command output
        $this->assertStringContainsString('IP blocklist updated successfully', Artisan::output());

        // Assert the cache contains the correct IPs
        $cachedIps = Cache::get('abuse_ips');
        $this->assertNotEmpty($cachedIps);
        $this->assertContains('192.168.0.1', $cachedIps);
        $this->assertContains('10.0.0.1', $cachedIps);
        $this->assertContains('1.2.3.4', $cachedIps);
        $this->assertContains('5.6.7.8', $cachedIps);
        $this->assertContains('127.0.0.9', $cachedIps);
        $this->assertNotContains('# Some Blocklist', $cachedIps);

        // Check that ip.json file is updated
        $ipjsonPath = config('abuseip.storage.path');
        $this->assertFileExists($ipjsonPath);
        $ipsfromFile = json_decode(file_get_contents($ipjsonPath), true);
        $this->assertNotEmpty($ipsfromFile);
        $this->assertContains('192.168.0.1', $ipsfromFile);
        $this->assertContains('10.0.0.1', $ipsfromFile);
        $this->assertContains('1.2.3.4', $ipsfromFile);
        $this->assertContains('5.6.7.8', $ipsfromFile);
        $this->assertContains('127.0.0.9', $ipsfromFile);
        $this->assertNotContains('# Some Blocklist', $ipsfromFile);
    }
}
