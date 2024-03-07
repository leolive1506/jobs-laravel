<?php

namespace App\Jobs\Middleware;

use App\Jobs\UserInfoJob;
use Closure;
use Illuminate\Support\Facades\RateLimiter;

class NotifyMiddleware
{
    /**
     * Process the queued job.
     *
     * @param  \Closure(object): void  $next
     */
    public function handle($job, Closure $next): void
    {
        $key = 'notifications';
        $perSeconds = 5;
        $decayRate = 10;

        info('Tentativas restantes: ' . RateLimiter::remaining($key, $perSeconds));
        $executed = RateLimiter::attempt(
            $key,
            $perSeconds,
            function() use ($next, $job) {
                info('in job');
                $next($job);
            },
            $decayRate
        );

        if (! $executed) {
          info('Too many messages sent!');
        }
    }
}
