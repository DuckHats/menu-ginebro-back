<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
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
    public function boot(): void
    {
        if (config('app.url')) {
            URL::forceRootUrl(config('app.url'));
            if (str_contains(config('app.url'), 'https://')) {
                URL::forceScheme('https');
            }
        }

        Gate::define('viewPulse', static function (?User $user) {
            return env('APP_ENV') == 'local';
        });
    }
}
