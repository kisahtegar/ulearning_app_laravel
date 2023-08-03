<?php

use Illuminate\Http\Request;
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

Route::group(['namespace'=>'Api'], function() {
    // Route::post('/login', [UserController::class, 'createUser']);
    Route::post('/login', 'UserController@createUser'); // since we using global namespace we should write like this.

    // authentication middleware.
    Route::group(['middleware' => ['auth:sanctum']], function() {
        Route::any('/courseList', 'CourseController@courseList');
        Route::any('/courseDetail', 'CourseController@courseDetail');
        Route::any('/checkout', 'PayController@checkout');
    });

    // This like our domain. example: https://737e-111-94-125-92.ngrok-free.app
    Route::any('/web_go_hooks', 'PayController@web_go_hooks');
});
