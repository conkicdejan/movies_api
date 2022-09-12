<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\MovieScoutController;
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

Route::group([
    'middleware' => ['api', 'auth'],
    'prefix' => 'movies',
    // 'controller' => MovieController::class
    'controller' => MovieScoutController::class
], function () {
    Route::get('', 'index');
    Route::get('topmovies', 'showTopMovies');
    Route::get('relatedmovies', 'showRelatedMovies');
    Route::post('', 'store');
    Route::get('{movie}', 'show');
    Route::delete('{movie}', 'destroy');
    Route::put('{movie}', 'update');
});


Route::group([
    'middleware' => ['api'],
    'prefix' => 'categories',
    'controller' => CategoryController::class
], function () {
    Route::get('', 'index');
});


Route::group([
    'middleware' => ['api'],
    'prefix' => 'comments',
    'controller' => CommentController::class
], function () {
    Route::post('', 'store')->middleware('auth');
});
