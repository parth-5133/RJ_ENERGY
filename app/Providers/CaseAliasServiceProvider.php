<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class CaseAliasServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        if (!class_exists('App\Http\Controllers\API\v1\ClientController')) {
            class_alias(
                \App\Http\Controllers\API\V1\ClientController::class,
                'App\Http\Controllers\API\v1\ClientController'
            );
        }
    }
}
