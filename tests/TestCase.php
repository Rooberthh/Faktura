<?php

namespace Rooberthh\Faktura\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use Rooberthh\Faktura\FakturaServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [
            FakturaServiceProvider::class,
        ];
    }

    /**
     * @return void
     * @param mixed $app
     */
    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }
}
