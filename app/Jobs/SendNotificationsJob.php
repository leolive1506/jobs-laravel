<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\SantamJobTop;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
         */
    public function handle(): void
    {
        User::limit(10)->get()->each(fn ($user) => SendNotificationJob::dispatch($user));
    }
}
