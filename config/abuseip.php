<?php

return [

    /*
    |--------------------------------------------------------------------------
    | AbuseIP Source URL
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

    /*
    |--------------------------------------------------------------------------
    | AbuseIP Storage
    |--------------------------------------------------------------------------
    |
    | The path to store the abuseip.json file which is cached upon retrieval.
    | By default using the compress option, where the IPs are stored as integers.
    | If you prefer to keep the IPs as strings and store them in a human-readable
    | format using JSON_PRETTY_PRINT, set the compress option to false.
    |
    */
    'storage' => [
        'path' => storage_path(
            env('ABUSEIP_STORAGE_PATH', 'framework/cache/abuseip.json')
        ),
        'compress' => env('ABUSEIP_STORAGE_COMPRESS', true),
    ],
];
