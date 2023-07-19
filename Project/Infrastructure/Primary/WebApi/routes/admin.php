<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/

Route::group([
    'middleware' => [ 'admin' ],
    'namespace' => 'Project\Infrastructure\Primary\WebApi\Controllers\Admin'
], function () {
    //Log
    Route::get('log', 'LogController@getLog');
    Route::get('log-dates', 'LogController@getAllFileLogs');
});
