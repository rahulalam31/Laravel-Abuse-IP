<?php

use Illuminate\Support\Facades\Cache;

return [

    /*
    |--------------------------------------------------------------------------
    | ABUSE_IP Source URL
    |--------------------------------------------------------------------------
    |
    | The source URLs yielding a list of abusive IPs. Change these to whatever
    | sources you like. Just make sure they are separated by newlines.
    |
    */
    'source' => [
        // https://github.com/borestad/blocklist-abuseipdb
        // Do not use the abuseipdb-s100-all.ipv4. It is only exposed for statistical usage.
        // Recommended usage is the maximum 30 days or less to avoid false positives.
        'https://raw.githubusercontent.com/borestad/blocklist-abuseipdb/main/abuseipdb-s100-30d.ipv4',
    ],

    'abuse_ips' => function () {
        return Cache::get('abuse_ips', function () {
            $path = config('abuseip.storage');
            return file_exists($path) ? json_decode(file_get_contents($path), true) : [];
        });
    },

    'storage' => storage_path('framework/cache/abuseip.json'),
];