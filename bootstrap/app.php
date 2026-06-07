<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();

$storagePath = $_SERVER['APP_STORAGE'] ?? $_ENV['APP_STORAGE'] ?? null;
if ($storagePath) {
    $app->useStoragePath($storagePath);
    $app->useBootstrapPath($storagePath . '/bootstrap');
    if (!is_dir($storagePath . '/bootstrap/cache')) {
        @mkdir($storagePath . '/bootstrap/cache', 0777, true);
    }
}

return $app;
