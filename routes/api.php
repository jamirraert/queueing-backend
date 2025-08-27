<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CounterController;
use App\Http\Controllers\QueueEntryController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\TellerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::controller(AuthController::class)->prefix('/auth')->name('auth.')->group(function() {
    Route::post('/authenticate', 'authenticate')->name('login');
    Route::post('/register', 'registerUser')->name('register');
});

Route::middleware('auth:sanctum')->group(function() {
    /**
     * @return {App\Models\Roles}
     */
    Route::controller(RoleController::class)->prefix('/role')->name('role.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::patch('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    /**
     * @return {App\Models\Counters}
     */
    Route::controller(CounterController::class)->prefix('/counter')->name('counter.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::patch('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    /**
     * @return { App\Models\Services }
     */
    Route::controller(ServiceController::class)->prefix('/service')->name('service.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::patch('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    /**
     * @return { App\Models\Teller }
     */
    Route::controller(TellerController::class)->prefix('/teller')->name('teller.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::patch('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });

    /**
     * @return { App\Models\QueueEntry }
     */
    Route::controller(QueueEntryController::class)->prefix('/queue')->name('queue.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
    });
});
