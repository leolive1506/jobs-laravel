<?php

namespace App\Jobs\Middleware;

use App\Jobs\UserInfoJob;
use Closure;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Redis;

class JobRateLimited
{
    /**
     * Process the queued job.
     *
     * @param  \Closure(object): void  $next
     */
    public function handle(UserInfoJob $job, Closure $next): void
    {
        $key = $job::class;
        $perMinute = 5;
        $decayRate = 60;

        info('Tentativas restantes: ' . RateLimiter::remaining($key, $perMinute));
        $executed = RateLimiter::attempt(
            $key,
            $perMinute,
            function() use ($next, $job) {
                info('in job');
                $next($job);
            },
            $decayRate
        );

        if (! $executed) {
          info('Too many messages sent!');
          $job->release(delay: 5);
        }
    }
}
