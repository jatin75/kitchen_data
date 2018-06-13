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
Route::middleware('auth:api')->group(function () {
    Route::get('userLogout', 'API\AdminHomeController@userLogout');

    /*job*/
	Route::post('changejobstatus','API\JobsController@changeJobStatus');
});
