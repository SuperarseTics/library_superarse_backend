<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\CheckTokenExpiration;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        apiPrefix: 'api/v1'
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'checktoken' => CheckTokenExpiration::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
