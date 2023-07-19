<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([
    'middleware' => [ 'web' ],
    'namespace' => 'Project\Infrastructure\Primary\Web\Controllers'
], function () {
    Route::get('/', 'TestController@index')->name('test');
});
