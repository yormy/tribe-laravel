<?php

namespace Yormy\TribeLaravel\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Yormy\TribeLaravel\TribeServiceProvider;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        TestConfig::setup();

        $this->withoutExceptionHandling();

        $this->updateEnv();

        $this->copyMigrations();

        $this->setupRoutes();
    }

    protected function updateEnv()
    {
        copy('./tests/Setup/.env', './vendor/orchestra/testbench-core/laravel/.env');
    }

    protected function copyMigrations()
    {
        $migrations = [
            '2020_09_12_000300_members.php',
        ];

        foreach ($migrations as $migration) {
            copy("./tests/Setup/Database/Migrations/$migration",
                "./vendor/orchestra/testbench-core/laravel/database/migrations/$migration");
        }
    }

    protected function setupRoutes()
    {
        //    TribeLaravelUploadRoutes::register();
        //    Route::TribeLaravelUpload();
    }

    protected function getPackageProviders($app)
    {
        return [
            TribeServiceProvider::class,
        ];
    }

    protected function setUpConfig(): void
    {
        dd('kk');

    }

    protected function refreshTestDatabase(): void
    {
        if (! RefreshDatabaseState::$migrated) {

            $this->artisan('db:wipe');

            $this->loadMigrationsFrom(__DIR__.'/../tests/Setup/Database/Migrations');
            $this->artisan('migrate');

            RefreshDatabaseState::$migrated = true;
        }

        $this->beginDatabaseTransaction();
    }
}
