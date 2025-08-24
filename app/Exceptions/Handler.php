<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Validation\ValidationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Illuminate\Http\Exception\HttpResponseException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
        'api_token',
        'secret',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            // Log all exceptions with context for production debugging
            if ($this->shouldReport($e)) {
                Log::error('Exception occurred', [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'url' => request()->fullUrl(),
                    'method' => request()->method(),
                    'user_id' => auth()->id(),
                    'user_agent' => request()->userAgent(),
                    'ip' => request()->ip(),
                    'trace' => config('app.debug') ? $e->getTraceAsString() : null,
                ]);
            }
        });

        // Handle specific exceptions with custom responses
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Resource not found',
                    'error_code' => 404
                ], 404);
            }

            return response()->view('errors.404', [], 404);
        });

        $this->renderable(function (MethodNotAllowedHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Method not allowed',
                    'error_code' => 405
                ], 405);
            }

            return response()->view('errors.405', [], 405);
        });

        $this->renderable(function (ModelNotFoundException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Model not found',
                    'error_code' => 404
                ], 404);
            }

            return response()->view('errors.404', [], 404);
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        // Handle validation exceptions
        if ($e instanceof ValidationException) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                    'error_code' => 422
                ], 422);
            }
        }

        // Handle authentication exceptions
        if ($e instanceof AuthenticationException) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated',
                    'error_code' => 401
                ], 401);
            }

            return redirect()->guest(route('login'));
        }

        // For production, show user-friendly error page
        if (!config('app.debug') && !$this->isHttpException($e)) {
            return response()->view('errors.500', [], 500);
        }

        return parent::render($request, $e);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
                'error_code' => 401
            ], 401);
        }

        return redirect()->guest(route('login'));
    }
}
