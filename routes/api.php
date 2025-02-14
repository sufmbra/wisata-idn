<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\OrderController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::middleware('/auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/user', [AuthController::class, 'me'])->middleware('auth:sanctum');


// CRUD
Route::middleware('auth:sanctum')->prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::post('/', [CategoryController::class, 'store']);
    Route::get('/{id}', [CategoryController::class, 'show']);
    Route::put('/{id}', [CategoryController::class, 'update']);
    Route::delete('/{id}', [CategoryController::class, 'destroy']);
});

Route::apiresource('products', ProductController::class);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/orders', [OrderController::class, 'index']);    // Get all orders
    Route::post('/orders', [OrderController::class, 'store']);   // Store new order
    Route::get('/orders/{id}', [OrderController::class, 'show']); // Show order by ID
});
