<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function(\Throwable $e, \Illuminate\Http\Request $request) {
            $status = match(true) {
                $e instanceof \Illuminate\Validation\ValidationException => 422,
                $e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException => 404,
                $e instanceof \Illuminate\Auth\AuthenticationException => 401,
                default => 500
            };

            $response = [
                'error' => true,
                'message' => $e->getMessage(),
                'data' => null
            ];

            if ($e instanceof \Illuminate\Validation\ValidationException) {
                $response['data'] = $e->errors();
            }

            if ($status === 500) {
                $response['message'] = config('app.debug') 
                    ? $e->getMessage() 
                    : 'Something went wrong on the server.';
            }

            return response()->json($response, $status);
        });
    })->create();
