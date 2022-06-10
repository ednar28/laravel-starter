<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::controller(AuthController::class)->middleware(['guest', 'throttle:10:2'])
    ->group(function () {
        Route::post('login', 'login')->name('login');
    });

Route::middleware('auth:sanctum')->group(function () {
    Route::controller(UserController::class)->prefix('user')->group(function () {
        Route::get('', 'index')->name('user.index');
        Route::post('', 'store')->name('user.create');
        Route::get('{user}', 'show')->name('user.show');
        Route::put('{user}', 'update')->name('user.update');
        Route::delete('{user}', 'destroy')->name('user.delete');
    });
});
