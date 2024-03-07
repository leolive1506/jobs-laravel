<?php

namespace App\Jobs;

use App\Models\User;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\SkipIfBatchCancelled;
use Illuminate\Queue\SerializesModels;

use function Psy\debug;

class TestExceptionJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected User $user,
        protected bool $needsToFail = false
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        sleep(5);
        info('Cancelado: ' . json_encode($this->batch()->cancelled()));
        if ($this->batch()->cancelled()) {
            info('batch cancelado');
            return;
        }

        info($this->user->name);


        if ($this->needsToFail) {
            throw new Exception(self::class, 1);
        }
    }

    public function middleware(): array
    {
        return [new SkipIfBatchCancelled()];
    }
}
