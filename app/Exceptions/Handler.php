<?php

namespace App\Exceptions;

use App\ErrorLog;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /** ErrorLog */
    protected $log;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  Throwable  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);

        if (!config('app.debug') && $this->shouldntReport($exception)) {
                $this->log = ErrorLog::log($exception);
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param  Throwable  $exception
     * @return Response
     */
    public function render($request, Throwable $exception)
    {

        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return Response
     */
//
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
//
    /**
     * Create a Symfony response for the given exception.
     *
     * @param  Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertExceptionToResponse(Throwable $e)
    {
        if (config('app.debug')) {
            return parent::convertExceptionToResponse($e);
        }

        view()->replaceNamespace('errors', [
            resource_path('views/errors'),
            __DIR__.'/views',
        ]);

        $log = $this->log;

        return \Symfony\Component\HttpFoundation\Response::create(view('errors::500', compact('e', 'log'))->render(), 500);
    }
}