<?php

namespace RahulAlam31\Middleware;

use Closure;
use Illuminate\Http\Request;

class AbuseIp
{
    protected $whitelistedIPs;

    public function __construct()
    {
        // Fetch the whitelist from config
        $this->whitelistedIPs = config('abuseip.whitelist', []);
    }

    public function handle(Request $request, Closure $next)
    {

        // Check if the IP is whitelisted
        if (in_array($request->ip(), $this->whitelistedIPs)) {
            return $next($request); // Allow request if IP is whitelisted
        }

        if (is_abused_ip($request->ip())) {

            // Log::info('Blocking IP: ' . $request->ip());
            return response('Your IP address has been blocked', 403);
        }

        return $next($request);
    }
}
