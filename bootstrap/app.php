<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Laravel 12 built-in API middleware
        $middleware->throttleApi();
        $middleware->statefulApi();

        // Basic middleware untuk web dan api
        $middleware->web(append: [
            // Web middleware jika diperlukan
        ]);

        $middleware->api(prepend: [
            // API middleware jika diperlukan
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
