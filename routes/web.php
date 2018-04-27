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

  Route::get('/','admin\AdminHomeController@showLogin');
  Route::get('login','admin\AdminHomeController@showLogin')->name('login');
  Route::post('dologin','admin\AdminHomeController@doLogin')->name('login.submit');
  Route::get('forgotpassword', 'admin\AdminHomeController@showForgotPassword')->name('forgotpassword');
  Route::post('sendemail', 'admin\AdminHomeController@sendForgotPasswordEmail')->name('request.frogotpwd');
  Route::get('resetpassword/{token}', 'admin\AdminHomeController@showResetPassword');
  Route::post('doresetpassword', 'admin\AdminHomeController@doResetPassword');
  Route::get('logout','admin\AdminHomeController@logout')->name('logout');

  Route::group(array('middleware'=>'CheckUser'),function(){
    /*Dashboard*/
    Route::get('dashboard','admin\AdminHomeController@showDashboard')->name('dashboard');
    Route::post('showjobdetailstatus','admin\AdminHomeController@showJobDetails')->name('showjobdetailstatus'); 

    /*Admin*/
    Route::get('profile/{email}','admin\AdminHomeController@editMyProfile')->name('profile');
    Route::post('storeadmin','admin\AdminHomeController@store')->name('storeadmin');
    Route::post('profile/adminchangepassword','admin\AdminHomeController@changePassword')->name('adminchangepassword');

    /*Jobs*/
    Route::get('jobs/activejobs','admin\JobsController@index')->name('activejobs');
    Route::get('jobs/deactivatedjobs','admin\JobsController@showDeactivated')->name('deactivatedjobs');
    Route::get('jobs/editjob/{job_id}','admin\JobsController@edit')->name('editjob');
    Route::get('jobs/deletejob/{job_id}','admin\JobsController@destroy')->name('deletejob');
    Route::get('jobs/deactivatejob/{job_id}','admin\JobsController@deactivateJob')->name('deactivatejob');

    /*Employees*/
    Route::get('employees/showemployees','admin\EmployeesController@index')->name('showemployees');
    Route::get('employees/addemployee','admin\EmployeesController@create')->name('addemployee');
    Route::get('employees/editemployee/{employee_id}','admin\EmployeesController@edit')->name('editemployee');
    Route::get('employees/deleteemployee/{employee_id}','admin\EmployeesController@destroy')->name('deleteemployee');
    Route::post('storeemployee','admin\EmployeesController@store')->name('storeemployee');

    /*Administration*/
    Route::get('administration/showclientcompany','admin\AdministrationController@index')->name('showclientcompany');
    Route::get('administration/editclientcompany/{company_id}','admin\AdministrationController@edit')->name('editclientcompany');
    Route::post('storeclientcompany','admin\AdministrationController@store')->name('storeclientcompany');
    Route::get('administration/deleteclientcompany/{company_id}','admin\AdministrationController@destroy')->name('deleteclientcompany');
    Route::get('administration/addclientcompany','admin\AdministrationController@create')->name('addclientcompany');

    /*Clients*/
    Route::get('clients/showclients','admin\ClientsController@index')->name('showclients');
    Route::get('clients/addclient','admin\ClientsController@create')->name('addclient');
    Route::get('clients/editclient/{client_id}','admin\ClientsController@edit')->name('editclient');
    Route::post('clients/storeclient','admin\ClientsController@store')->name('storeclient');
    Route::get('clients/deleteclient/{client_id}','admin\ClientsController@destroy')->name('deleteclient');

    /*Reports*/
    Route::get('reports/showreports','admin\ReportsController@index')->name('showreports');
    Route::post('downloadjobexcel','admin\ReportsController@downloadJobExcel')->name('downloadjobexcel');
  });
