<?php

namespace RahulAlam31\LaravelAbuseIp\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class UpdateAbuseIps extends Command
{
    protected $signature = 'abuseip:update';
    protected $description = 'update the abuse ip list.';

    public function handle()
    {
        $this->info('Fetching IP blockList...');

        //fetch the IP blocklist
        $ips = $this->fetchIpsFromSources(config('abuseip.source'));

        if(!empty($ips)){

            //save to ip.json
            file_put_contents(config('abuseip.storage'), json_encode($ips, JSON_PRETTY_PRINT));

            Cache::forever('abuse_ips', $ips);

            $this->info('IP blocklist updated successfully');
        } else {
            $this->error('Failed to fetch IP blocklist');
        }

    }

    private function fetchIpsFromSources(array $sources)
    {
        $ips = [];
        foreach ($sources as $source) {
            $response = Http::get($source);
            if ($response->successful()) {
                $sourceIps = array_filter(explode("\n", $response->body()));
                $ips = array_merge($ips, $sourceIps);
            } else {
                $this->error("Failed to fetch from source: $source");
            }
        }
        return array_unique($ips);
    }
}