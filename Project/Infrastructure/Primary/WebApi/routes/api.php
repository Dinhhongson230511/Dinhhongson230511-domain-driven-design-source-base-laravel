<?php

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


// Api routes with authentication
Route::group([
    'prefix' => 'v2',
    // 'middleware' => ['api'],
    'namespace' => 'Project\Infrastructure\Primary\WebApi\Controllers\Api'
], function () {
    Route::get('test', 'UserController@index')->name('test');

    // Notification
    Route::prefix('notification')->group(function () {
        Route::get('notification-email-message/mark-as-read', 'NotificationController@markEmailNotificationAsRead')
            ->name('notification-email-history.verify');
    });
});
