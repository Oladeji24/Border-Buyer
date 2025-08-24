<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class EnsureUserIsActive
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
        if (!Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Check if user is active
        if (!$user->isActive()) {
            Auth::logout();

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Your account has been deactivated. Please contact support.',
                    'reason' => $user->suspension_reason ?? 'Account deactivated'
                ], 403);
            }

            return Redirect::route('login')->with([
                'error' => 'Your account has been deactivated. Please contact support.',
                'reason' => $user->suspension_reason ?? 'Account deactivated'
            ]);
        }

        // Update last login information
        if ($request->isMethod('get') && !$request->ajax()) {
            $user->updateLastLogin();
        }

        return $next($request);
    }
}