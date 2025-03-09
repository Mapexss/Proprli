<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::apiResource('tasks', TaskController::class)->only(['index']);
Route::post('tasks/{building}', [TaskController::class, 'store'])->name('tasks.store');
Route::post('comments/{task}', [CommentController::class, 'store'])->name('comments.store');
