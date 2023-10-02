<?php

namespace Yormy\TribeLaravel\ServiceProviders;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();

        $this->map();

    }

    public function map(): void
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
    }

    protected function mapWebRoutes(): void
    {
    }

    protected function mapApiRoutes(): void
    {
    }
}
