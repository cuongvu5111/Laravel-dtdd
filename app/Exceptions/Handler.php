<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // if($exception instanceof NotFoundHttpException)
        // {
        //     return response()->view('errors.503', [], 404);
        // }
        // else if ($exception instanceof ModelNotFoundException)
        // {
        // // Do your stuff here
        //     return response()->view('errors.503', [], 404); 
        // }
        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if($request->expectsJson()) {
            return response()->json(['message' => $exception->getMessage()], 401);
        }

        $guards = $exception->guards();
        $firstGuard = array_get($guards, 0);
        switch($firstGuard) {
            case 'admin':
                return redirect('admin/login');
            default:
                return redirect('/login');
        }
    }
}
