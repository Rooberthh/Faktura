<?php

namespace Rooberthh\Faktura;

use Illuminate\Support\ServiceProvider;

class FakturaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes(
            [
                __DIR__ . '/../config/faktura.php' => config_path('faktura.php'),
            ],
            'faktura-config',
        );

        $this->publishesMigrations(
            [
                __DIR__ . '/../database/migrations' => $this->app->databasePath('migrations'),
            ],
            'faktura-migrations',
        );
    }

    public function register(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->mergeConfigFrom(__DIR__ . '/../config/faktura.php', 'faktura');
    }
}
