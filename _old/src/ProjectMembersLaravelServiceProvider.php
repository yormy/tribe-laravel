<?php

namespace Yormy\ProjectMembersLaravel;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Yormy\ProjectMembersLaravel\Providers\EventServiceProvider;

class ProjectMembersLaravelServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {

            $this->publishAssets();

            $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');

            $this->registerCommands();
        }

        $this->registerGuestRoutes();
        $this->registerUserRoutes();
        $this->registerAdminRoutes();
    }

    private function publishAssets()
    {
        $this->publishes([
            __DIR__ . '/../config/project-members-laravel.php' => config_path('project-members-laravel.php'),
        ], 'config');

        // todo : publish dummy projects migration
        $this->publishes([
            __DIR__ . '/../resources/views/blade' => base_path('resources/views/vendor/project-members-laravel'),
        ], 'blade');

        $this->publishes([
            __DIR__ . '/../resources/views/vue' => base_path('resources/views/vendor/project-members-laravel'),
            __DIR__ . '/../resources/assets' => resource_path('assets/vendor/project-members-laravel'),
        ], 'vue');

        $this->publishMigrations();
    }


    private function registerCommands()
    {
//        $this->commands([
//            ...::class,
//        ]);
    }

    private function publishMigrations()
    {
        $migrations = [
            'create_referral_actions_table.php',
            'create_referral_domains_table.php',
            'create_referral_payments_table.php',
            'create_referral_awards_table.php',
            'seed_referral_actions_table.php',
        ];

        $index = 0;
        foreach ($migrations as $migrationFileName) {
            if (! $this->migrationFileExists($migrationFileName)) {
                $sequence = date('Y_m_d_His', time());
                $newSequence = substr($sequence, 0, strlen($sequence) - 2);
                $paddedIndex = str_pad(strval($index), 2, '0', STR_PAD_LEFT);
                $newSequence .= $paddedIndex;
                $this->publishes([
                    __DIR__ . "/../database/migrations/{$migrationFileName}.stub" => database_path('migrations/' . $newSequence . '_' . $migrationFileName),
                ], 'migrations');

                $index++;
            }
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/project-members-laravel.php', 'project-members-laravel');
        $this->app->register(EventServiceProvider::class);
    }

    private function registerGuestRoutes()
    {
    }

    private function registerUserRoutes()
    {
        Route::macro('ProjectMembersLaravelUser', function (string $prefix) {
            Route::prefix($prefix)->name($prefix. ".")->group(function () {
                Route::get('/details', [ReferrerDetailsController::class, 'show'])->name('show');
            });
        });
    }

    private function registerAdminRoutes()
    {
        //  Route::get('/admin1/ref/details/{referrer}', [ReferrerDetailsController::class, 'showForUser'])->name('shownow');

        Route::macro('ProjectMembersLaravelAdmin', function (string $prefix) {
            Route::prefix($prefix)->name($prefix. ".")->group(function () {
                Route::get('/referrers', [ReferrerOverviewController::class, 'index'])->name('overview');
                Route::get('/referrers/{referrer}', [ReferrerDetailsController::class, 'showForUser'])->name('showForUser');
            });
        });
    }

    public static function migrationFileExists(string $migrationFileName): bool
    {
        $len = strlen($migrationFileName);
        foreach (glob(database_path("migrations/*.php")) as $filename) {
            if ((substr($filename, -$len) === $migrationFileName)) {
                return true;
            }
        }

        return false;
    }
}
