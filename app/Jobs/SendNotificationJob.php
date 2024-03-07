<?php

namespace App\Jobs;

use App\Jobs\Middleware\JobRateLimited;
use App\Jobs\Middleware\NotifyMiddleware;
use App\Models\User;
use App\Notifications\SantamJobTop;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
// use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

// use Illuminate\Support\Facades\RateLimiter;

class SendNotificationJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 0;

    /**
     * Create a new job instance.
     */
    public function __construct(public User $user)
    {
    }
    /**
     * Execute the job.

     */
    public function handle(): void
    {
        $this->user->notify(new SantamJobTop());
    }

    public function middleware(): array
    {
        return [
            new NotifyMiddleware()
        ];
    }
}
