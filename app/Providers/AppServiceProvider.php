<?php

namespace App\Providers;

use Carbon\Carbon;
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
        // Returns a new Carbon instance set to the authenticated user's timezone
        Carbon::macro('userTimezone', function (): Carbon {
            return $this->tz(auth()->user()?->timezone ?? config('app.timezone'));
        });
    }
}
