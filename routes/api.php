<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;

// Route::get('tasks', [TaskController::class, 'index']);
// Route::get('tasks/{task}', [TaskController::class, 'show']);
// Route::post('tasks/{task}', [TaskController::class, 'store']);
// Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
// Route::put('tasks/{task}', [TaskController::class, 'update']);

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);

    Route::middleware('role:admin')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::post('users/{user}/role/{role}', [UserController::class, 'AssignRoleToUser']);
        Route::get('users/{user}/restore', [UserController::class, 'RestoreDeletedUser']);
    });

    Route::middleware(['role:admin|manager'])->group(function () {
        Route::apiResource('tasks', TaskController::class);
        Route::get('tasks/{task}/restore', [TaskController::class, 'RestoreDeletedTask']);
    });

    Route::post('tasks/{id}/assign', [TaskController::class, 'assignTask'])->middleware('role:manager');
    Route::post('tasks/{task}/updatestatus', [TaskController::class, 'updateStatus'])->middleware('role:user');
});
