<?php

namespace rahulalam31\AbuseIp\Middleware;


use Closure;
use Illuminate\Http\Request;

class AbuseIp
{
    public function handle(Request $request, Closure $next)
    {
        $abuseip = config('abuse-ip');

        if(in_array($request->ip(), $abuseip)) {
            abort(403, 'Your IP address has been blocked');
        }

        return $next($request);
    }
}