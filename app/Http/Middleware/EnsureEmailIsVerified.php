<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use App\Providers\RouteServiceProvider;

class EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() ||
            (!$request->user()->hasVerifiedEmail())) {
            return $request->expectsJson()
                    ? response()->json(['message' => 'Your email address is not verified.'], 409)
                    : Redirect::route('verification.notice');
        }

        return $next($request);
    }
}