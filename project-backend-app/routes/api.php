<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\UserController;
use \App\Http\Controllers\ProjectController;
use \App\Http\Controllers\TaskController;

Route::post('login', [UserController::class, 'index']);

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::apiResources([
        'project' => ProjectController::class,
        'task'=> TaskController::class,
    ]);
    Route::get('tasks/{projectId}', [TaskController::class, 'getTasksByProject'])->name('getTasksByProject');
});
