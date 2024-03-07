<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
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
        // $perMinutes = 5;
        // $minute = 1;
        // RateLimiter::attempt('notifications', $perMinutes, function () {}, $minute);
        // RateLimiter::for('backups', function (object $job) {
        //     return $job->user->vipCustomer()
        //                 ? Limit::none()
        //                 : Limit::perMinute(2)->by($job->user->id);
        // });
    }
}
