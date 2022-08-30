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
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth',
    'controller' => AuthController::class
], function ($router) {
    Route::post('register', 'register');
    Route::post('login', 'login');
    Route::post('logout', 'logout')->middleware('auth');
    Route::get('me', 'me')->middleware('auth');
});
