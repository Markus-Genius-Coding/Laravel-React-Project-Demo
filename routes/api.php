<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\API\RegisterController;
use Illuminate\Http\Request;
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


Route::group(["middleware"=>"permissioncheck:User"], function() {
    Route::prefix('projects') ->group(function () {
        Route::get('', [ProjectController::class, 'getAll']);
        Route::post('', [ProjectController::class, 'create']);
        Route::patch('/{id}', [ProjectController::class, 'patch']);
        Route::get('/{id}', [ProjectController::class, 'getById']);
        Route::delete('/{id}', [ProjectController::class, 'delete']);
    });
});

// AUTH and Register Routes
Route::prefix('user') ->group(function () {
    Route::post('register', [RegisterController::class, 'registerUser']);
    Route::post('login', [AuthController::class, 'doLogin']);
});
