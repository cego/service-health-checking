<?php

namespace Cego\ServiceHealthChecking;

use Illuminate\Support\ServiceProvider;

class ServiceHealthServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../../publishable/service-health-checking.php' => config_path('service-health-checking.php')
        ]);

        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
    }
}
