<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;

class AbuseIp
{
    public function handle(Request $request, Closure $next)
    {
        $abuseip = config('abuseip.abuse_ips')();

        if (in_array($request->ip(), $abuseip)) {

            // Log::info('Blocking IP: ' . $request->ip());
            return response('Your IP address has been blocked', 403);
        }

        return $next($request);
    }
}
