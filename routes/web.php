<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

// TODO: delete this route
Route::get('/home', 'HomeController@index')->name('home');

/**
 * Admin panel
 */
Route::namespace('Admin')->prefix('admin')->middleware('auth')->group(function () {

    /**
     * Requirements
     */
    //Route::resource('/admin/requirements', 'RequirementController')->names('admin.requirements');
    Route::get('/requirements', 'RequirementController@index')->name('admin.requirements.index');
    Route::get('/requirements/create', 'RequirementController@create')->name('admin.requirements.create');
    Route::post('/requirements/store', 'RequirementController@store')->name('admin.requirements.store');
    Route::get('/requirements/show/{requirement}', 'RequirementController@show')->name('admin.requirements.show');
    Route::get('/requirements/history/{rule_reference}', 'RequirementController@history')->name('admin.requirements.history');

    /**
     * Flows
     */
    // Route::resource('/flows', 'FlowController')->names('admin.flows');
    Route::get('/flows', 'FlowController@index')->name('admin.flows.index');
    Route::get('/flows/create/{requirement}', 'FlowController@create')->name('admin.flows.create');
    Route::post('/flows/store', 'FlowController@store')->name('admin.flows.store');
    Route::get('/flows/show/{flow}', 'FlowController@show')->name('admin.flows.show');
    Route::post('/flows/{flow}', 'FlowController@update')->name('admin.flows.update');
    Route::get('/flows/show/{flow}/requirement/{rule_reference}/edit', 'FlowController@ajaxGetRuleReference')->name('admin.flows.ajax_rule_reference');




});
