<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MessageController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/messages', [MessageController::class, 'index']);
    Route::get('/chat', [MessageController::class, 'chat']);
    Route::post('/messages', [MessageController::class, 'store']);
    Route::delete('/messages/{message}',
        [MessageController::class, 'destroy'])->name('messages.destroy');
    Route::get('/messages/{message}/edit',
        [MessageController::class, 'edit'])->name('messages.edit');
    Route::put('/messages/{message}',
        [MessageController::class, 'update']);

    Route::get('/chat', [MessageController::class, 'chat'])->name('chat');

});

require __DIR__.'/auth.php';
