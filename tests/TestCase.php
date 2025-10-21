<?php

namespace Rooberthh\Faktura\Tests;

use Illuminate\Support\Arr;
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

    protected function defineDatabaseMigrations(): void
    {
        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
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

    public function loadFixture($file, $key = null, $override = null, $value = null)
    {
        $fixture = json_decode(file_get_contents(__DIR__ . "/Fixtures/{$file}.json"), true);

        if (!empty($key)) {
            $fixture = $fixture[$key];
        }

        if (! is_null($override)) {
            $replacement = is_array($override) ? $override : [$override => $value];

            foreach ($replacement as $override => $value) {
                Arr::set($fixture, $override, $value);
            }
        }

        return $fixture;
    }
}
