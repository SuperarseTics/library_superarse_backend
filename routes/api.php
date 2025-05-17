<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| This routes avoid CSRF Token
|
*/

// Auth routes
Route::post('login', [AuthController::class, 'authenticate'])->middleware(['guest','throttle:5,1']);
Route::delete('logout', [AuthController::class, 'logout'])->middleware(['auth:sanctum','checktoken']);

// Book routes
Route::prefix('books')->group(function () {
    Route::middleware(['auth:sanctum','checktoken'])->group(function () {
        Route::get('/filter', [BookController::class, 'filter']);
        Route::get('/catalog', [BookController::class, 'catalog']);
        Route::get('', [BookController::class, 'show']);
        Route::post('/', [BookController::class, 'store']);
        Route::post('/upd', [BookController::class, 'update']);
        Route::delete('/{code}', [BookController::class, 'destroy']);
        Route::get('/download', [BookController::class, 'download']);
    });
});

// Category routes
Route::middleware(['auth:sanctum','checktoken'])->group(function () {
    Route::group(['prefix' => 'categories'], function () {
        Route::get('/catalog', [CategoryController::class, 'catalog']);
        Route::get('', [CategoryController::class, 'show']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::post('/upd', [CategoryController::class, 'update']);
        Route::delete('/{code}', [CategoryController::class, 'destroy']);
    });
});

// Setting routes
Route::middleware(['auth:sanctum','checktoken'])->group(function () {
    Route::group(['prefix' => 'settings'], function () {
        Route::get('/rules', [SettingController::class, 'rules']);
        Route::get('/', [SettingController::class, 'index']);
        Route::put('/upd', [SettingController::class, 'update']);
    });
});

// Booking routes
Route::middleware(['auth:sanctum','checktoken'])->group(function () {
    Route::group(['prefix' => 'bookings'], function () {
        Route::get('/record', [BookingController::class, 'record']);
        Route::get('', [BookingController::class, 'show']);
        Route::put('/reserve', [BookingController::class, 'reserve']);
        Route::put('/delivery', [BookingController::class, 'delivery']);
        Route::put('/giveback', [BookingController::class, 'giveback']);
    });
});

// Dashboard routes
Route::middleware(['auth:sanctum','checktoken'])->group(function () {
    Route::group(['prefix' => 'dashboards'], function () {
        Route::get('/', [DashboardController::class, 'generate']);
    });
});
