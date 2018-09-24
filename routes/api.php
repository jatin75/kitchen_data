<?php

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

Route::post('login', 'API\AdminHomeController@login');
Route::post('forgotPassword', 'API\AdminHomeController@forgotPassword');
Route::middleware('auth:api')->group(function () {
    //-------------- User --------------
    Route::get('userLogout', 'API\AdminHomeController@userLogout');
    Route::post('changeAccountSetting', 'API\AdminHomeController@changeAccountSetting');
    Route::post('changeMyProfile', 'API\AdminHomeController@changeMyProfile');

    //-------------- Jobs --------------
    Route::post('getUserJobDetails', 'API\JobsController@getUserJobDetails');
    Route::post('changeJobStatus', 'API\JobsController@changeJobStatus');

    //-------------- Chat --------------
    Route::post('chatPost', 'API\AdminHomeController@chatPost');
});
