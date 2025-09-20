<?php

namespace Rooberthh\Faktura;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseRouteServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends BaseRouteServiceProvider
{
    public function boot(): void
    {
        Route::middleware('web')->group(__DIR__ . '/../routes/routes.php');
    }
}
