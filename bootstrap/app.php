<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckDevice; // Middleware untuk cek perangkat
use App\Http\Middleware\IsAdmin;   // Middleware untuk cek admin
use App\Http\Middleware\LoadSettingsFromDatabase; // <-- 1. Tambahkan use statement ini

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // Daftarkan middleware Anda di sini

        // Tambahkan middleware ke grup 'web'
        $middleware->web(append: [
            CheckDevice::class,
            LoadSettingsFromDatabase::class, // <-- 2. Tambahkan baris ini
        ]);

        // Daftarkan alias 'admin' untuk middleware IsAdmin
        $middleware->alias([
            'admin' => IsAdmin::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();