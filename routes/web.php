<?php

use App\Jobs\SendNotificationsJob;
use App\Jobs\UserInfoJob;
use App\Models\User;
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
