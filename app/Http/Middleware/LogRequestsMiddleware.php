<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class LogRequestsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $start = microtime(true);

        Log::info('Request', [
            'method' => $request->method(),
            'url'    => $request->fullUrl(),
            'ip'     => $request->ip(),
            'agent'  => $request->userAgent(),
            'user'   => $request->user()?->id,
        ]);

        $response = $next($request);

        $duration = round((microtime(true) - $start) * 1000, 2);

        Log::info('Response', [
            'status'   => $response->getStatusCode(),
            'url'      => $request->fullUrl(),
            'duration' => $duration . 'ms',
        ]);

        return $response;
    }
}
