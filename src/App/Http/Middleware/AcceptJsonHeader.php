<?php

namespace Jeybin\Networkintl\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AcceptJsonHeader
{
    public function handle(Request $request, Closure $next)
    {
        /**
         * Adding accept header to all requests
         */
        $request->headers->set('Accept', 'application/json');

        return $next($request);
    }
}