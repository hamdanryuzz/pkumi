<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\StudentClass;
use App\Observers\StudentClassObserver;

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
    public function boot(): void
    {
        //
        StudentClass::observe(StudentClassObserver::class);
    }
}
