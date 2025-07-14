<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\AuthenticateController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;



Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [AuthenticateController::class, 'login']);
Route::get('/logout', [AuthenticateController::class, 'logout'])->middleware('auth:sanctum');


Route::middleware([AdminMiddleware::class, 'auth:sanctum'])->get('/admin', function () {
    return 'Welcome to Admin Panel!';
});

Route::prefix('/admin')->middleware(['auth:sanctum', AdminMiddleware::class])->group(function () {
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/products', ProductController::class);
});