<?php

namespace RahulAlam31\LaravelAbuseIp\Middleware;

use Closure;
use Illuminate\Http\Request;

class AbuseIp
{
    public function handle(Request $request, Closure $next)
    {
        if (is_abused_ip($request->ip())) {
            // Log::info('Blocking IP: ' . $request->ip());

            return response('Your IP address has been blocked', 403);
        }

        return $next($request);
    }
}
