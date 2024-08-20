<?php

use Illuminate\Support\Facades\Cache;

if (! function_exists('abuse_ips')) {
    function abuse_ips(): array
    {
        return Cache::get('abuse_ips', function () {
            $path = config('abuseip.storage.path');

            return file_exists($path) ? json_decode(file_get_contents($path), true) : [];
        });
    }
}

if (! function_exists('is_abused_ip')) {
    function is_abused_ip(string|int $ip): bool
    {
        if (is_string($ip) && config('abuseip.storage.compress')) {
            $ip = is_numeric($ip) ? (int) $ip : ip2long($ip);
        }

        return in_array($ip, abuse_ips(), true);
    }
}
