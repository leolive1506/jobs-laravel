<?php

namespace App\Jobs;

use App\Console\Commands\SendNotification;
use App\Models\User;
use App\Notifications\SantamJobTop;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;

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
        $jobs = [];
        foreach (User::limit(10)->get() as $user) {
            $jobs[] = new SendNotificationJob($user);
        }

        Bus::batch($jobs)->name('Sending notificaitons')->dispatch();


        // User::limit(10)->get()->each(fn ($user) => SendNotificationJob::dispatch($user));
    }
}
