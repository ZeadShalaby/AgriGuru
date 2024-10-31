<?php

use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Notifications;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SensorController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['middleware' => ['api']], function () {

    // ? not Authenticated  login regist
    Route::POST('/login', [UsersController::class, 'login']);
    Route::POST('/register', [UsersController::class, 'register']);
    Route::POST('/forge', [UsersController::class, 'forget']);
    Route::POST('/verify-code', [UsersController::class, 'verifycode']);

    // ? must be Authenticated
    Route::group(['middleware' => ['auth.guard:api']], function () {
        Route::get('/info-user', [UsersController::class, 'edit']);
        Route::put('/edit-user', [UsersController::class, 'update']);
        Route::POST('/logout', [UsersController::class, 'logout']);
        Route::POST('/read-notification/{id}', [Notifications::class, 'readNotification']);
        Route::get('/notification', [Notifications::class, 'notification']);

        // ? return all sensor for this user 
        Route::group(['middleware' => ['owner.sensor']], function () {
            Route::get('/sensors', [SensorController::class, 'index'])->middleware('can:view-sensor');
            Route::get('/sensors/{type}', [SensorController::class, 'show'])->middleware('can:view-sensor');
            Route::post('/sensors', [SensorController::class, 'store'])->middleware('can:create-sensor');
            Route::get('/sensors/search', [SensorController::class, 'AutoComplete'])->middleware('can:view-sensor');
        });
    });

    // ?verify email
    Route::POST('/verify/{id}', [UsersController::class, 'verify'])->name('verifyEmail');

});


