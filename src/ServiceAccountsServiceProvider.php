<?php

namespace Openwod\ServiceAccounts;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Openwod\ServiceAccounts\Models\ServiceAccount;

class ServiceAccountsServiceProvider extends ServiceProvider
{

    public function boot()
    {
        // Publish configuration file
        $this->publishes([
            __DIR__ . '/../config/service-accounts.php' => config_path('service-accounts.php'),
            __DIR__ . '/../config/sanctum.php' => config_path('sanctum.php')
        ]);
        // Make migrations available
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Publish routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }

    public function register() {}
}
