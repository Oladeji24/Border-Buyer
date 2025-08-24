<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;

class RateLimitByIP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $maxAttempts
     * @param  int  $decayMinutes
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $maxAttempts = 60, $decayMinutes = 1)
    {
        $key = $this->resolveRequestSignature($request);

        if ($this->tooManyAttempts($key, $maxAttempts, $decayMinutes)) {
            return $this->buildResponse($key, $maxAttempts);
        }

        $this->hit($key, $decayMinutes);

        return $next($request);
    }

    /**
     * Resolve request signature.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function resolveRequestSignature($request)
    {
        return sha1(
            $request->ip() . '|' . $request->route()->getName() . '|' . $request->method()
        );
    }

    /**
     * Determine if the given key has been "accessed" too many times.
     *
     * @param  string  $key
     * @param  int  $maxAttempts
     * @param  int  $decayMinutes
     * @return bool
     */
    protected function tooManyAttempts($key, $maxAttempts, $decayMinutes)
    {
        return Cache::get($key, 0) >= $maxAttempts;
    }

    /**
     * Increment the counter for a given key.
     *
     * @param  string  $key
     * @param  int  $decayMinutes
     * @return int
     */
    protected function hit($key, $decayMinutes)
    {
        $expiresAt = now()->addMinutes($decayMinutes);
        
        return Cache::add($key, 1, $expiresAt) ? 1 : Cache::increment($key);
    }

    /**
     * Create a 'too many attempts' response.
     *
     * @param  string  $key
     * @param  int  $maxAttempts
     * @return \Illuminate\Http\Response
     */
    protected function buildResponse($key, $maxAttempts)
    {
        $seconds = Cache::get($key . ':timer', 60);

        $headers = [
            'X-RateLimit-Limit' => $maxAttempts,
            'X-RateLimit-Remaining' => 0,
            'X-RateLimit-Reset' => $seconds,
            'Retry-After' => $seconds,
            'Content-Type' => 'application/json',
        ];

        $response = [
            'success' => false,
            'message' => 'Too many attempts. Please try again later.',
            'retry_after' => $seconds,
        ];

        return Response::json($response, 429, $headers);
    }
}