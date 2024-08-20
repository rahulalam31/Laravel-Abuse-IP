<?php

namespace RahulAlam31\LaravelAbuseIp\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class UpdateAbuseIps extends Command
{
    protected $signature = 'abuseip:update';

    protected $description = 'update the abuse ip list.';

    public function handle(): void
    {
        $this->info('Fetching IP blockList...');

        // fetch the IP blocklist
        $ips = $this->fetchIpsFromSources(config('abuseip.source'));

        if (empty($ips)) {
            $this->error('Failed to fetch IP blocklist');

            return;
        }

        // convert ips to integers
        $ips = array_map(fn(string $ip) => ip2long($ip), $ips);

        // save to abuseip.json
        file_put_contents(config('abuseip.storage'), json_encode($ips));

        try {
            Cache::forever('abuse_ips', $ips);

            $this->info('IP blocklist updated successfully');
        } catch (QueryException) {
            Cache::forget('abuse_ips');

            $this->warn('IP blocklist saved to file, but is too long to cache in database');
        }
    }

    private function fetchIpsFromSources(array $sources): array
    {
        $ips = [];
        foreach ($sources as $source) {
            $response = Http::get($source);
            if ($response->successful()) {
                $sourceIps = $this->parseBlocklist($response->body());
                $ips = array_merge($ips, $sourceIps);
            } else {
                $this->error("Failed to fetch from source: $source");
            }
        }

        return array_values(array_unique($ips));
    }

    private function parseBlocklist(string $blocklist): array
    {
        $lines = explode("\n", $blocklist);

        // Remove inline comments and validate that every line contains a valid IP address
        return array_filter(
            array_map(fn($line) => preg_replace('/\s*#.*$/', '', trim($line)), $lines),
            fn($line) => filter_var($line, FILTER_VALIDATE_IP) !== false
        );
    }
}
