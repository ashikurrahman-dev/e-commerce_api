<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Auth\AuthenticateController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;




Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [AuthenticateController::class, 'login']);
// admin login
Route::post('/admin/login', [AuthenticateController::class, 'login']);
Route::get('/logout', [AuthenticateController::class, 'logout'])->middleware('auth:sanctum');


Route::middleware([AdminMiddleware::class, 'auth:sanctum'])->get('/admin', function () {
    return 'Welcome to Admin Panel!';
});

Route::prefix('/admin')->middleware(['auth:sanctum', AdminMiddleware::class])->group(function () {
    Route::apiResource('/categories', CategoryController::class);
    Route::apiResource('/products', ProductController::class);
    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::patch('/orders/{id}/status', [OrderController::class, 'updateStatus']);
});

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/products', [App\Http\Controllers\ProductController::class, 'index']);
    Route::get('/products/{id}', [App\Http\Controllers\ProductController::class, 'show']);
});