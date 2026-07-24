<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Jobs\SendWelcomeEmailJob;
use App\Http\Controllers\Web\TaskController;


Route::get('/', function () {
    return redirect('/tasks');
});
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

Route::get('/tasks/create', [TaskController::class, 'create'])
    ->name('tasks.create');

Route::post('/tasks', [TaskController::class, 'store'])
    ->name('tasks.store');

Route::get('/tasks', [TaskController::class, 'index'])
    ->name('tasks.index');


Route::view('/tasks', 'tasks.index')->name('tasks.index');
Route::view('/tasks/create', 'tasks.create')->name('tasks.create');
Route::view('/tasks/{id}/edit', 'tasks.edit')
    ->name('tasks.edit');Route::get('/test-queue', function () {
    SendWelcomeEmailJob::dispatch('test@example.com');

    return 'Job dispatched!';


});
