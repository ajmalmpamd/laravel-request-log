<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;


class LogRequestResponseTime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Config::get('logging.requests.enabled', false)) {
            return $next($request);
        }

        $startTime = microtime(true);

        $response = $next($request);

        $endTime = microtime(true);
        $duration = $endTime - $startTime;

        $this->logRequest($request, $duration);

        return $response;
    }

    protected function logRequest(Request $request, float $duration)
    {
        DB::table('request_logs')->insert([
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'response_time' => $duration,
            'created_at' => now(),
        ]);
    }
}
