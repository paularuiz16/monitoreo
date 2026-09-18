<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (HttpException $e, Request $request) {
            if ($e->getStatusCode() === 419) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'message' => 'Tu sesión o token de seguridad ha expirado. Por favor recarga la página.',
                    ], 419);
                }

                return redirect()->route('login')
                    ->with('error', 'Tu sesión o token de seguridad expiró por inactividad. Se ha renovado automáticamente, por favor ingresa nuevamente.');
            }
        });

        $exceptions->render(function (TokenMismatchException $e, Request $request) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Tu sesión o token de seguridad ha expirado. Por favor recarga la página.',
                ], 419);
            }

            return redirect()->route('login')
                ->with('error', 'Tu sesión o token de seguridad expiró por inactividad. Se ha renovado automáticamente, por favor ingresa nuevamente.');
        });
    })->create();

