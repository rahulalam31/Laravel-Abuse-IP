<?php

namespace RahulAlam31\LaravelAbuseIp\Middleware;

use Closure;
use Illuminate\Http\Request;

class AbuseIp
{
    protected array $whitelistedIPs;
    protected array $blacklistedIPs;

    public function __construct()
    {
        // Fetch the whitelist from config
        $this->whitelistedIPs = config('abuseip.whitelist', []);
        $this->blacklistedIPs = config('abuseip.blacklist', []);
    }

    public function handle(Request $request, Closure $next)
    {
        $ip = $request->ip();

        // Check if the IP is whitelisted
        if (in_array($ip, $this->whitelistedIPs)) {
            return $next($request); // Allow request if IP is whitelisted
        }

        // check if the IP is blacklisted in the config
        if (in_array($ip, $this->blacklistedIPs)) {
            // Log::info('Blocking IP: ' . $ip);
            abort(403, 'Your IP address has been blocked');
        }

        if (is_abused_ip($ip)) {
            // Log::info('Blocking IP: ' . $ip);
            abort(403, 'Your IP address has been blocked');
        }

        return $next($request);
    }
}
