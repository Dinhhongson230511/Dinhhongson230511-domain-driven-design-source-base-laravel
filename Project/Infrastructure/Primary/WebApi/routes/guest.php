<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
|
| Here is where you can register guest routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "public" middleware group. Now create something great!
|
*/

// Api routes without authentication
Route::group([
    'prefix' => 'api/v2',
    'middleware' => ['public'],
    'namespace' => 'Project\Infrastructure\Primary\WebApi\Controllers\Api'
], function () {

    // Ping
    Route::get('ping', function () {
        return response('pong', 200);
    });

});

// Api routes with session
Route::group([
    'prefix' => 'api/v2',
    'middleware' => ['web'],
    'namespace' => 'Project\Infrastructure\Primary\WebApi\Controllers\Api'
], function () {

    // Authentication Routes
    Route::get('auth/login/facebook', 'AuthenticationController@redirectToFacebookProvider')->name('auth-login-fb');
    Route::get('facebook/callback', 'AuthenticationController@handleFacebookProviderCallback')->name('facebook-callback');
    Route::get('auth/login/line', 'AuthenticationController@redirectToLineProvider')->name('auth-login-line');
    Route::get('line/callback', 'AuthenticationController@handleLineProviderCallback')->name('line-callback');
});

// Admin routes without authentication
Route::group([
    'prefix' => 'admin',
    'middleware' => ['public'],
    'namespace' => 'Project\Infrastructure\Primary\WebApi\Controllers\Admin'
], function () {

    // Default landing page
    Route::get('/', function () {
        return view('welcome');
    });

    // Ping
    Route::get('ping', function () {
        return response('pong', 200);
    });
});
