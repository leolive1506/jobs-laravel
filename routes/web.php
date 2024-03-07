<?php

use App\Jobs\MakeOrder;
use App\Jobs\RunPayment;
use App\Jobs\SendNotificationsJob;
use App\Jobs\UserInfoJob;
use App\Jobs\ValidateCard;
use App\Models\User;
use Illuminate\Bus\BatchRepository;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('send-notifications-to-all', function () {
    SendNotificationsJob::dispatch();

    return 'Finish';
});

Route::get('user-info', function () {
    UserInfoJob::dispatch(
        User::where('email', 'leonardolivelopes2@gmail.com')->firstOrFail()
    );

    return 'Finish';
});


Route::get('run-batch', function () {
    Bus::batch([
        new MakeOrder,
        new ValidateCard,
        new RunPayment
    ])
    ->name('Run batch example ' . rand(1, 10))
    ->dispatch();

    return redirect('/');
});


Route::get('/', function (BatchRepository $batchRepository) {
    return view('welcome', [
        'batches' => $batchRepository->get()
    ]);
});
