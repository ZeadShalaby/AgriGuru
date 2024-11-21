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


// ? not Authenticated  login regist
Route::POST('/register', [UsersController::class, 'register']);
Route::POST('/forget', [UsersController::class, 'forget']);
Route::POST('/verify-code', [UsersController::class, 'verifycode']);

Route::POST('/login', [UsersController::class, 'login']);

Route::group(['middleware' => ['auth.guard:api', 'verify']], function () {


    // ? must be Authenticated
    Route::get('/info-user', [UsersController::class, 'edit']);
    Route::put('/edit-user', [UsersController::class, 'update']);
    Route::POST('/change-img', [UsersController::class, 'changeimg']);
    Route::POST('/logout', action: [UsersController::class, 'logout']);
    Route::POST('/read-notification/{id}', [Notifications::class, 'readNotification']);
    Route::get('/notification', [Notifications::class, 'notification']);

    // ? return all sensor for this user 
    Route::group(['middleware' => ['owner.sensor']], function () {
        Route::get('/sensors', [SensorController::class, 'index']);
        Route::get('/sensors/{type}', [SensorController::class, 'show']);
        Route::post('/sensors', [SensorController::class, 'store']);
        Route::get('/sensors/search', [SensorController::class, 'AutoComplete']);
    });
});


// ?verify email
Route::POST('/verify/{id}', [UsersController::class, 'verify'])->name('verifyEmail');
// ?return image user
Route::get('/imageusers/{img}', [UsersController::class, 'imagesuser']);



