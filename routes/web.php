<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get('/', 'admin\AdminHomeController@showLogin');
Route::get('login', 'admin\AdminHomeController@showLogin')->name('login');
Route::post('dologin', 'admin\AdminHomeController@doLogin')->name('login.submit');
Route::get('forgotpassword', 'admin\AdminHomeController@showForgotPassword')->name('forgotpassword');
Route::post('sendemail', 'admin\AdminHomeController@sendForgotPasswordEmail')->name('request.frogotpwd');
Route::get('resetpassword/{token}', 'admin\AdminHomeController@showResetPassword');
Route::post('doresetpassword', 'admin\AdminHomeController@doResetPassword');
Route::get('logout', 'admin\AdminHomeController@logout')->name('logout');

Route::group(array('middleware' => 'CheckUser'), function () {
    /*Dashboard*/
    Route::get('dashboard', 'admin\AdminHomeController@showDashboard')->name('dashboard');
    Route::post('showjobdetailstatus', 'admin\AdminHomeController@showJobDetails')->name('showjobdetailstatus');

    /*My Profile*/
    //Route::get('profile/{email}','admin\AdminHomeController@editMyProfile')->name('adminprofile');
    //Route::post('storeadmin','admin\AdminHomeController@store')->name('storeadmin');
    Route::post('profile/changepassword', 'admin\AdminHomeController@changePassword')->name('changepassword');

    /*Jobs*/
    Route::get('jobs/activejobs', 'admin\JobsController@index')->name('activejobs');
    Route::get('jobs/deactivatedjobs', 'admin\JobsController@showDeactivated')->name('deactivatedjobs');
    Route::get('jobs/editjob/{job_id}', 'admin\JobsController@edit')->name('editjob');
    Route::get('jobs/addjob', 'admin\JobsController@create')->name('addjob');
    Route::post('jobs/storejob', 'admin\JobsController@store')->name('storejob');
    Route::get('jobs/deletejob/{job_id}', 'admin\JobsController@destroy')->name('deletejob');
    Route::get('jobs/deactivatejob/{job_id}', 'admin\JobsController@deactivateJob')->name('deactivatejob');
    Route::post('jobs/storejobnote', 'admin\JobsController@storeJobNote')->name('storejobnote');
    Route::post('jobs/viewjobdetails', 'admin\JobsController@viewJobDetails')->name('viewjobdetails');
    Route::post('jobs/showaudittrail', 'admin\JobsController@showAuditTrail')->name('showaudittrail');
    Route::post('jobs/changejobstatus', 'admin\JobsController@changeJobStatus')->name('changejobstatus');
    Route::post('jobs/sendmailchangejobstatus', 'admin\JobsController@SendMailChangeJobStatus')->name('sendmailchangejobstatus');
    Route::post('jobs/editnote', 'admin\JobsController@editNote')->name('editnote');
    Route::post('jobs/destroynote', 'admin\JobsController@destroyNote')->name('destroynote');
    Route::post('jobs/editjobmodel', 'admin\JobsController@editJobDateTimeModel')->name('editjobdatetimemodel');

    /*Employees*/
    Route::get('employees/showemployees', 'admin\EmployeesController@index')->name('showemployees');
    Route::get('employees/addemployee', 'admin\EmployeesController@create')->name('addemployee');
    Route::get('employees/editemployee/{employee_id}', 'admin\EmployeesController@edit')->name('editemployee');
    Route::get('employees/deleteemployee/{employee_id}', 'admin\EmployeesController@destroy')->name('deleteemployee');
    Route::post('storeemployee', 'admin\EmployeesController@store')->name('storeemployee');
    Route::get('profile/{id}', 'admin\EmployeesController@editMyProfile')->name('employeeprofile');

    /*Administration*/
    Route::get('administration/showclientcompany', 'admin\AdministrationController@index')->name('showclientcompany');
    Route::get('administration/editclientcompany/{company_id}', 'admin\AdministrationController@edit')->name('editclientcompany');
    Route::post('storeclientcompany', 'admin\AdministrationController@store')->name('storeclientcompany');
    Route::get('administration/deleteclientcompany/{company_id}', 'admin\AdministrationController@destroy')->name('deleteclientcompany');
    Route::get('administration/addclientcompany', 'admin\AdministrationController@create')->name('addclientcompany');

    /*Clients*/
    Route::get('clients/showclients', 'admin\ClientsController@index')->name('showclients');
    Route::get('clients/addclient', 'admin\ClientsController@create')->name('addclient');
    Route::get('clients/editclient/{client_id}', 'admin\ClientsController@edit')->name('editclient');
    Route::post('clients/storeclient', 'admin\ClientsController@store')->name('storeclient');
    Route::get('clients/deleteclient/{client_id}', 'admin\ClientsController@destroy')->name('deleteclient');
    Route::get('myprofile/{id}', 'admin\ClientsController@editMyProfile')->name('clientprofile');
    Route::post('clients/getcompanyclients', 'admin\ClientsController@getCompanyClients')->name('getcompanyclients');

    /*Reports*/
    Route::get('reports/showreports', 'admin\ReportsController@index')->name('showreports');
    Route::post('downloadjobexcel', 'admin\ReportsController@downloadJobExcel')->name('downloadjobexcel');

});