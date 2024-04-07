<?php

declare(strict_types=1);

namespace Yormy\TribeLaravel;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Yormy\TribeLaravel\ServiceProviders\EventServiceProvider;
use Yormy\TribeLaravel\ServiceProviders\RouteServiceProvider;

class TribeServiceProvider extends ServiceProvider
{
    public const CONFIG_FILE = __DIR__.'/../config/tribe.php';

    public const CONFIG_FILE_CHUNKED = __DIR__.'/../config/chunk-upload.php';

    public const CONFIG_IDE_HELPER_FILE = __DIR__.'/../config/ide-helper.php';

    /**
     * @psalm-suppress MissingReturnType
     */
    public function boot(Router $router): void
    {
        $this->publish();

        $this->registerCommands();

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->registerTranslations();

        $this->morphMaps();
    }

    /**
     * @psalm-suppress MixedArgument
     */
    public function register(): void
    {
        $this->mergeConfigFrom(static::CONFIG_FILE, 'tribe');
        $this->mergeConfigFrom(static::CONFIG_IDE_HELPER_FILE, 'ide-helper');

        $this->app->register(EventServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
    }

    public function registerTranslations(): void
    {
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'tribe');
    }

    private function publish(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                self::CONFIG_FILE => config_path('tribe.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations'),
            ], 'migrations');

            $this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/tribe'),
            ], 'translations');
        }
    }

    private function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
            ]);
        }
    }

    private function morphMaps(): void
    {
    }
}
