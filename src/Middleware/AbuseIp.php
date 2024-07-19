<?php

namespace RahulAlam31\LaravelAbuseIp\Middleware;


use Closure;
use Illuminate\Http\Request;

class AbuseIp
{
    public function handle(Request $request, Closure $next)
    {
        $abuseip = config('abuse-ip.spam_ips');

        Log::info('Request IP: ' . $request->ip());
        Log::info('Spam IPs: ', $abuseip);
        if (in_array($request->ip(), $abuseip)) {

            Log::info('Blocking IP: ' . $request->ip());
            return response('Your IP address has been blocked', 403);
        }

        return $next($request);
    }
}
