<?php

use Illuminate\Support\Facades\Route;
use App\Jobs\SendWelcomeEmailJob;
use App\Http\Controllers\Web\TaskController;


Route::get('/', function () {
    return redirect('/tasks');
});


Route::view('/login', 'auth.login')
    ->name('login');


Route::view('/register', 'auth.register')
    ->name('register');


// Страница всех задач
Route::get('/tasks', [TaskController::class, 'index'])
    ->name('tasks.index');


// Страница создания задачи
Route::get('/tasks/create', [TaskController::class, 'create'])
    ->name('tasks.create');


// Создание задачи через Blade
Route::post('/tasks', [TaskController::class, 'store'])
    ->name('tasks.store');


// Страница публичных задач
Route::get('/public-tasks', [TaskController::class, 'publicTasks'])
    ->name('tasks.public');


// Страница редактирования задачи
Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])
    ->name('tasks.edit');

Route::get('/public-tasks/{task}/edit',
    [TaskController::class, 'editPublic']
)->name('public-tasks.edit');


Route::put('/public-tasks/{task}',
    [TaskController::class, 'updatePublic']
)->name('public-tasks.update')->middleware([
    'auth:api',
    'permission:public-tasks.update']);

// Тест очереди
Route::get('/test-queue', function () {

    SendWelcomeEmailJob::dispatch('test@example.com');

    return 'Job dispatched!';

});
