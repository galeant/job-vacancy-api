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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->redirectGuestsTo(function (\Illuminate\Http\Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'Unauthorized',
                ], 401);
            }

            return route('login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (\Throwable $e, $request) {

            if ($request->is('api/*')) {
                $msg = $e->getMessage();
                $code = 500;
                $validations = null;

                if($e instanceof \Illuminate\Validation\ValidationException){
                    $msg = 'Validation error';
                    $code = 422;
                    $validations = $e->errors();
                } elseif ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    $msg = 'Unauthenticated';
                    $code = 401;
                } elseif ($e instanceof \Illuminate\Auth\Access\AuthorizationException) {
                    $msg = 'Unauthorized';
                    $code = 403;
                } elseif ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                    $msg = 'Resource not found';
                    $code = 404;
                } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                    $msg = 'Endpoint not found';
                    $code = 404;
                } elseif ($e instanceof \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException) {
                    $msg = 'Method not allowed';
                    $code = 405;
                }

                return response()->json([
                    'message' => $msg,
                    'validations' => $validations,
                ], $code);
            }
        });
    })->create();
