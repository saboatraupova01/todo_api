<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\TaskController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES
|--------------------------------------------------------------------------
*/
    /*
    |---------------- USERS ----------------
    */
    Route::middleware(['auth:api'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])
            ->middleware('permission:users.view');
        Route::post('/users', [UserController::class, 'store'])
            ->middleware('permission:users.create');
        Route::put('/users/{user}', [UserController::class, 'update'])
            ->middleware('permission:users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])
            ->middleware('permission:users.delete');

    Route::post('/users/{user}/roles', [UserController::class, 'assignRoles']);

    /*
    |---------------- ROLES ----------------
    */
    Route::get('/roles', [RoleController::class, 'index'])->middleware('permission:roles.view');
    Route::get('/roles/{role}', [RoleController::class, 'show'])->middleware('permission:roles.view');
    Route::post('/roles', [RoleController::class, 'store'])->middleware('permission:roles.create');
    Route::put('/roles/{role}', [RoleController::class, 'update'])->middleware('permission:roles.update');
    Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->middleware('permission:roles.delete');

    Route::post('/roles/{role}/permissions', [RoleController::class, 'assignPermissions'])
        ->middleware('permission:roles.update');

    /*
    |-------------- PERMISSIONS -------------
    */
    Route::get('/permissions', [PermissionController::class, 'index'])->middleware('permission:permissions.view');
    Route::get('/permissions/{permission}', [PermissionController::class, 'show'])->middleware('permission:permissions.view');
    Route::post('/permissions', [PermissionController::class, 'store'])->middleware('permission:permissions.create');
    Route::put('/permissions/{permission}', [PermissionController::class, 'update'])->middleware('permission:permissions.update');
    Route::delete('/permissions/{permission}', [PermissionController::class, 'destroy'])->middleware('permission:permissions.delete');
    Route::middleware(['auth:api', 'permission:users.update'])
        ->post('/users/{user}/permissions', [UserController::class, 'assignPermissions']);
    /*
    |---------------- TASKS ----------------
    */
    Route::get('/tasks', [TaskController::class, 'index'])
        ->middleware('permission:tasks.view');

    Route::post('/tasks', [TaskController::class, 'store'])
        ->middleware('permission:tasks.create');

    Route::get('/tasks/{task}', [TaskController::class, 'show'])
        ->middleware('permission:tasks.view');

    Route::put('/tasks/{task}', [TaskController::class, 'update'])
        ->middleware('permission:tasks.update');

    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])
        ->middleware('permission:tasks.delete');
});
