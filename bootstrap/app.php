<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
<<<<<<< HEAD
=======
        api: __DIR__.'/../routes/api.php',
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
<<<<<<< HEAD
        //
=======
        // Add CORS middleware for API routes
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);
        
        // Exclude webhook endpoints from CSRF protection
        $middleware->validateCsrfTokens(except: [
            'api/webhooks/*',
            'api/webhook/*',
            'api/xendit/*',
        ]);
>>>>>>> a4f7a035c1848f938bab5ae49cff16cb399118b3
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
