<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PointController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ScaleController;
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

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
});

Route::group(['prefix' => 'users', 'middleware' => 'auth:api'], function () {
    Route::get('/', [UserController::class, 'getItems'])->middleware('auth.permissions:View');
    Route::post('/', [UserController::class, 'create'])->middleware('auth.permissions:Create');
    Route::put('/{id}', [UserController::class, 'update'])->middleware('auth.permissions:Update');
    Route::delete('/{id}', [UserController::class, 'delete'])->middleware('auth.permissions:Delete');
});

Route::group(['prefix' => 'scales', 'middleware' => 'auth:api'], function () {
    Route::get('/', ScaleController::class . '@getItems')->middleware('auth.permissions:View');
    Route::post('/', ScaleController::class . '@create')->middleware('auth.permissions:Create');
    Route::put('/{id}', ScaleController::class . '@update')->middleware('auth.permissions:Update');
    Route::delete('/{id}', ScaleController::class . '@delete')->middleware('auth.permissions:Delete');
});

Route::group(['prefix' => 'points', 'middleware' => 'auth:api'], function () {
    Route::get('/', PointController::class . '@getItems')->middleware('auth.permissions:View');
    Route::post('/', PointController::class . '@create')->middleware('auth.permissions:Create');
    Route::put('/{id}', PointController::class . '@update')->middleware('auth.permissions:Update');
    Route::delete('/{id}', PointController::class . '@delete')->middleware('auth.permissions:Delete');
});
