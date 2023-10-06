<?php

namespace Yormy\TribeLaravel\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Yormy\TribeLaravel\Tests\Setup\Routes\TribeLaravelUploadRoutes;
use Yormy\TribeLaravel\TribeServiceProvider;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    private $useMinio = true;

    protected function setUp(): void
    {
        $this->updateEnv();

        $this->copyMigrations();

        parent::setUp();

        $this->withoutExceptionHandling();

        $this->setUpConfig();

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
        config(['filestore' => require __DIR__.'/../config/tribe.php']);
        config(['app.key' => 'base64:yNmpwO5YE6xwBz0enheYLBDslnbslodDqK1u+oE5CEE=']);

        config(['filesystems.disks.local.root' => getcwd().'/tests/Setup/Storage/encryption']);

        if ($this->useMinio) {
            $this->setUpDiskMinio();
        } else {
            $this->setUpDiskCi();
        }
    }

    protected function setUpDiskCi(): void
    {
        $s3LocalFaked = [
            'driver' => 'local',
            'root' => getcwd().'/tests/Setup/Storage/localfake',
            'throw' => false,
        ];

        config(['filesystems.disks.digitalocean' => $s3LocalFaked]);
    }

    protected function setUpDiskMinio(): void
    {
        $s3Storage = [
            'driver' => 's3',
            'root' => env('DO_STORAGE_ROOT'),
            'key' => env('DO_STORAGE_ACCESS_KEY_ID'),
            'secret' => env('DO_STORAGE_SECRET_ACCESS_KEY'),
            'endpoint' => env('DO_STORAGE_ENDPOINT'),
            'use_path_style_endpoint' => env('DO_STORAGE_PATH_STYLE', false),
            'version' => 'latest',
            'region' => env('DO_STORAGE_DEFAULT_REGION'),
            'bucket' => env('DO_STORAGE_BUCKET'),
        ];

        config(['filesystems.disks.digitalocean' => $s3Storage]);
    }

    protected function refreshTestDatabase()
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
