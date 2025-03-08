<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\UserController;
use App\Http\Controllers\api\ProductController;
use App\Http\Controllers\api\SaleController;
use App\Http\Controllers\api\SaleProductController;

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

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'store']);
    Route::get('products/{id}', [ProductController::class, 'show']); 
    Route::put('products/{id}', [ProductController::class, 'update']); 

    Route::post('sales', [SaleController::class, 'store']);
    Route::put('sales/{id}/confirm', [SaleController::class, 'confirm']);
    Route::delete('sales/{id}/cancel', [SaleController::class, 'cancel']);

    Route::post('sales/{id}/products', [SaleProductController::class, 'store']);
    Route::delete('sales/{saleId}/products/{productId}', [SaleProductController::class, 'delete']);
    
    Route::middleware('isAdmin')->group(function () {
        Route::get('users', [UserController::class, 'index']);
        Route::post('users', [UserController::class, 'store']);
        Route::get('users/{id}', [UserController::class, 'show']); 
        Route::put('users/{id}', [UserController::class, 'update']); 
        Route::delete('users/{id}', [UserController::class, 'delete']);

        Route::delete('products/{id}', [ProductController::class, 'delete']); 

        Route::get('sales', [SaleController::class, 'index']);
        Route::get('sales/{id}', [SaleController::class, 'showWithProducts']);
    });
});
