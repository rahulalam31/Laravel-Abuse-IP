<?php

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
        // 30 Days list
        // 'https://raw.githubusercontent.com/borestad/blocklist-abuseipdb/main/abuseipdb-s100-30d.ipv4',
        // 14 Days list
        'https://raw.githubusercontent.com/borestad/blocklist-abuseipdb/main/abuseipdb-s100-14d.ipv4'
    ],

    /*
    |--------------------------------------------------------------------------
    | IP Whitelist
    |--------------------------------------------------------------------------
    |
    | The IP addresses listed here will bypass the blocking middleware.
    | You can add IPs as strings, and they will be checked before blocking logic.
    |
    */
    'whitelist' => [
        '127.0.0.1', // Localhost example
        // Add more IPs here...
    ],

    'storage' => storage_path('framework/cache/abuseip.json'),
];
