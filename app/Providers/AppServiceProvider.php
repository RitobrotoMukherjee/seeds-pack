<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ReturnService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ReturnService::class, function($app) {
            return new ReturnService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
