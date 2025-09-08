<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        ini_set('upload_max_filesize', '20M');
        ini_set('post_max_size', '25M');
        ini_set('memory_limit', '256M');
    }
}
