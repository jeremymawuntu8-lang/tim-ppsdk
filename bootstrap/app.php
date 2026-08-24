<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
    $middleware->trustProxies(at: '*');   // <-- baris baru

    $middleware->web(append: [
        \App\Http\Middleware\LogUserActivity::class,
        ]);

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'active' => \App\Http\Middleware\EnsureUserIsActive::class,
            'company.active' => \App\Http\Middleware\EnsureCompanyIsActive::class,
            'internal' => \App\Http\Middleware\EnsureUserIsInternal::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();