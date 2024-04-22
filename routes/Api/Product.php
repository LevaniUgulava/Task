<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
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

Route::middleware('auth')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['api', 'cors']], function () {
    Route::prefix('/cart')->group(function () {
        Route::post('add/{product}', [CartController::class, 'add']);
        Route::post('delete/{product}', [CartController::class, 'delete']);
    });

    Route::prefix('/product')->group(function () {
        Route::post('/store', [ProductController::class, 'store']);
        Route::post('/delete', [ProductController::class, 'destroy']);
        Route::post('/checkout', [ProductController::class, 'checkOut']);
    });
});
