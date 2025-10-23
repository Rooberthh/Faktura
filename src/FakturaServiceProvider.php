<?php

namespace Rooberthh\Faktura;

use Illuminate\Support\ServiceProvider;
use Rooberthh\Faktura\Console\Commands\CreateInvoiceCommand;
use Rooberthh\Faktura\Contracts\GatewayContract;
use Rooberthh\Faktura\Services\Stripe\StripeGateway;

class FakturaServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
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

            $this->commands(
                [
                    CreateInvoiceCommand::class,
                ],
            );
        }

        $this->app->register(RouteServiceProvider::class);
        $this->app->bind(GatewayContract::class, StripeGateway::class);
    }

    public function register(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->mergeConfigFrom(__DIR__ . '/../config/faktura.php', 'faktura');
    }
}
