<?php

namespace App\Console\Commands;

use App\Jobs\TestExceptionJob;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;
use Throwable;

class TestExceptionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'teb';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test exception on batch';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user1 = User::query()->offset(0)->first();
        $user2 = User::query()->offset(1)->first();
        $user3 = User::query()->offset(3)->first();

        $user4 = User::query()->offset(4)->first();
        $user5 = User::query()->offset(5)->first();
        $user6 = User::query()->offset(6)->first();

        $users = User::query()->take(10)->offset(10)->get()->map(fn ($u) => new TestExceptionJob($u, false));

        // dois chain dentro de um batch
        Bus::batch([
            ...$users,
            [
                new TestExceptionJob($user1, true),
                new TestExceptionJob($user2, false),
                new TestExceptionJob($user3, false)
            ],
            [
                new TestExceptionJob($user4, false),
                new TestExceptionJob($user5, true),
                new TestExceptionJob($user6, false)
            ]
        ])
        // ->catch(function (Batch $batch, Throwable $e) {
        //     // First batch job failure detected...
        //     info('Primeiro job falhou: ' . $e);
        //     $batch->cancel();
        // })
        ->dispatch();
    }
}
