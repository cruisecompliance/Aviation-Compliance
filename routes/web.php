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

Route::get('/home', 'HomeController@index')->name('home');

// Requirements
Route::namespace('Admin')->middleware('auth')->group(function () {
    //Route::resource('/admin/requirements', 'RequirementController')->names('admin.requirements');
    Route::get('/admin/requirements', 'RequirementController@index')->name('admin.requirements.index');
    Route::get('/admin/requirements/create', 'RequirementController@create')->name('admin.requirements.create');
    Route::post('/admin/requirements/store', 'RequirementController@store')->name('admin.requirements.store');
    Route::get('/admin/requirements/show/{version}', 'RequirementController@show')->name('admin.requirements.show');

});
