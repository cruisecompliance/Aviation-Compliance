<?php

use Illuminate\Support\Facades\Route;
use App\Enums\RoleName;
use App\Enums\PermissionName;

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

Route::get('/', function () {
    return view('welcome');
});

// TODO:: delete
Route::get('/starter', function () {
    return view('starter');
});

Auth::routes(['register' => false]);
//Auth::routes();

/**
 * User panel
 */
Route::namespace('User')->prefix('user')->middleware('auth', 'role:'.RoleName::ACCOUNTABLE_MANAGER.'|'.RoleName::COMPLIANCE_MONITORING_MANAGER.'|'.RoleName::AUDITOR.'|'.RoleName::AUDITEE.'|'.RoleName::INVESTIGATOR)->group(function () {

    /**
     * User homapege
     */
    Route::get('/home', 'HomeController@index')->name('user.dashboard');

});


/**
 * Admin panel
 */
Route::namespace('Admin')->prefix('admin')->middleware('auth', 'role:'.RoleName::SME)->group(function () {

    /**
     * Admin homepage
     */
    Route::get('/home', 'HomeController@index')->name('admin.dashboard');

    /**
     * Users
     **/
    Route::resource('/users', 'UserController')->except(['create','show','destroy'])->names('admin.users');

    /**
     * Companies
     **/
    Route::resource('/companies', 'CompanyController')->except(['create','show','destroy'])->names('admin.companies');


    /**
     * Requirements
     */
    //Route::resource('/admin/requirements', 'RequirementController')->names('admin.requirements');
    Route::get('/requirements', 'RequirementController@index')->name('admin.requirements.index');
    Route::post('/requirements/store', 'RequirementController@store')->name('admin.requirements.store');
    Route::get('/requirements/{requirement}', 'RequirementController@show')->name('admin.requirements.show');
    Route::get('/requirements/history/{rule_reference}', 'RequirementController@history')->name('admin.requirements.history');

    /**
     * Flows
     */
    Route::namespace('Flows')->group(function () {

        // Flows
        Route::resource('/flows', 'FlowController')->except(['create','show','update','destroy'])->names('admin.flows');

        // Flow Requirements
        Route::get('/flows/{flow}/requirements', 'RequirementController@index')->name('admin.flows.requirements.index');
        Route::get('/flows/{flow}/requirements/{rule_reference}/edit', 'RequirementController@edit')->name('admin.flows.requirements.edit');
        Route::post('/flows/{flow}/requirements', 'RequirementController@update')->name('admin.flows.requirements.update');
    });


});
