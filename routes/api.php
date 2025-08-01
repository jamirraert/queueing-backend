<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CounterOptionsController;
use App\Http\Controllers\CounterServiceController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\ServiceOptionsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/**
 * @param {GREETING}
 */

Route::get('/greeting', function () {
    return [
        'greeting' => 'Hello World!'
    ];
});

Route::prefix('/auth')
    ->controller(AuthController::class)
    ->name('auth.')
    ->group(function() {
        Route::post('/login', 'login')
            ->name('login');
        Route::post('/register', 'register')
            ->name('register');
    });

/**
 * Routes for CounterOptions
 * 
 * Applies sanctum with middleware
 * @method CREATE, READ, UPDATE, DELETE
 */
Route::middleware('auth:sanctum')
    ->prefix('/options')
    ->controller(CounterOptionsController::class)
    ->name('options.')
    ->group(function() {
        Route::get('/counter', 'index')
            ->name('counter.index');
        Route::post('/counter', 'store')
            ->name('counter.store');
        Route::patch('/counter/{id}', 'update')
            ->name('counter.update');
        Route::delete('/counter/{id}', 'destroy')
            ->name('counter.destroy');
    });

/**
 * Routes for ServiceOptions
 * 
 * Applies sanctum with middleware
 * @method CREATE, READ, UPDATE, DELETE
 */
Route::middleware('auth:sanctum')
    ->prefix('/options')
    ->controller(ServiceOptionsController::class)
    ->name('options.')
    ->group(function() {
        
        Route::get('/service', 'index')
            ->name('service.index');

        Route::post('/service', 'store')
            ->name('service.store');

        Route::patch('/service/{id}', 'update')
            ->name('service.update');

        Route::delete('/service/{id}', 'destroy')
            ->name('service.destroy');
    });

/**
 * Route for Counter and Service
 * 
 * User get to choose what counter
 * and service he/she is
 * @method {CREATE, READ, UPDATE, DELETE}
 */
Route::middleware('auth:sanctum')
    ->prefix('/counter/service')
    ->controller(CounterServiceController::class)
    ->name('staff.')
    ->group(function() {

        Route::get('/staff/{user_id}', 'find')
            ->name('find');
        Route::post('/staff', 'store')
            ->name('store');

    });
/**
 * Route for Queue
 * 
 * User call the waiting queue
 * @method {POST, PATCH}
 */
Route::middleware('auth:sanctum')
    ->prefix('/queue')
    ->controller(QueueController::class)
    ->name('queue.')
    ->group(function() {

        Route::post('/','store')
            ->name('store');
        Route::patch('/call/{id}/{user_id}', 'call')
            ->name('call');
        Route::get('/display/{department_id}/{status}', 'display')
            ->name('display');
    });

