<?php

use Illuminate\Support\Facades\Route;
use App\Jobs\SendWelcomeEmailJob;


Route::get('/', function () {
    return view('welcome');
});



Route::get('/test-queue', function () {
    SendWelcomeEmailJob::dispatch('test@example.com');

    return 'Job dispatched!';
});
