<?php

namespace App\Http;

class Kernel extends \Illuminate\Foundation\Http\Kernel
{
    
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Cookie\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
    ];

    
    protected $routeMiddleware = [];
}
