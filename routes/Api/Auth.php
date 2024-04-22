<?php

use App\Http\Controllers\AuthController;
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



Route::group(['middleware' => ['api', 'cors'], 'prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
    Route::post('edit', [AuthController::class, 'edit'])->name('edit');
});


Route::group(['middleware' => ['api', 'cors']], function () {

    Route::prefix('password/')->group(function () {
        Route::post('forgot', [AuthController::class, 'forgetPassword']);
        Route::post('reset', [AuthController::class, 'reset']);
    });
});
