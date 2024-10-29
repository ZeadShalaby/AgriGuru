<?php

use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsersController;

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


    Route::POST('/login', [UsersController::class, 'login']);

    Route::POST('/register', [UsersController::class, 'register']);

    Route::group(['middleware' => ['auth.guard:api']], function () {
        Route::get('/info-user/{user}', [UsersController::class, 'edit']);
        Route::POST('/logout', [UsersController::class, 'logout']);
    });

});


