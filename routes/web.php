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

/**
 * Main page (login form)
 */
Route::get('/', 'Auth\LoginController@showLoginForm')->name('login');

/**
 * login microsoft azure ad
 */
Route::get('/login/azure', 'Auth\LoginController@redirectToProvider')->name('azure.login');
Route::get('/login/azure/callback', 'Auth\LoginController@handleProviderCallback');

/**
 * MS TEAMS
 */
Route::get('/login/teams', 'Auth\TeamController@login')->name('auth.login.teams');
Route::get('/login/teams-silent-start', 'Auth\TeamController@silent_start')->name('auth.login.teams_silent_start');
Route::get('/login/teams-silent-end', 'Auth\TeamController@silent_end')->name('auth.login.teams_silent_end');
Route::get('/login/teams/profile/{email}/name/{displayName?}', 'Auth\TeamController@profile')->name('auth.login.profile');

/**
 * Pages
 */
Route::get('/privacy', function() {
    return view('pages.privacy');
});

Route::get('/terms', function() {
    return view('pages.terms');
});


/*
 * Auth
 */
Auth::routes(['register' => false]);
//Auth::routes();


/**
 * Components
 */
Route::namespace('Components')->middleware('auth')->group(function () {

    /**
     * Flow Requirements
     */
    Route::namespace('Flows')->group(function () {
        // Flow Filter
        Route::get('/flows/{flow?}/filter/show', 'FilterController@showFilterForm')->name('components.flows.filters.show');
        Route::post('/flows/{flow}/filter/store', 'FilterController@store')->name('components.flows.filters.store');
        Route::delete('/flows/filter/{name?}/destroy', 'FilterController@destroy')->name('components.flows.filters.destroy');
        // Route::post('/flows/{flow?}/filter/search', 'FilterController@search')->name('components.flows.filters.search');

        // Flow Calendar
        Route::get('/calendar/{hash}', 'CalendarController@index')->name('components.flows.calendar.index')->withoutMiddleware('auth');

        // Flow Requirements (Edit Form)
        Route::get('/flows/{flow}/requirements/{rule_reference}/edit', 'RequirementController@edit')->name('components.flows.requirements.edit');
        Route::post('/flows/{flow}/requirements/', 'RequirementController@update')->name('components.flows.requirements.update');

        // Flow Requirements (Multiple Edit)
        Route::get('/flows/{flow}/multiple/requirements/edit', 'RequirementController@multipleEdit')->name('components.flows.multiple.requirements.edit');
        Route::post('/flows/{flow}/multiple/requirements/', 'RequirementController@multipleUpdate')->name('components.flows.multiple.requirements.update');

        // Flow comment
        Route::get('/flows/{rule_id}/comments', 'CommentController@index')->name('components.flows.comments.show');
        Route::post('/flows/{flow?}/comment/store', 'CommentController@store')->name('components.flows.comments.store');

        // Flow History
        Route::get('flows/{rule_id}/history', 'HistoryController@show')->name('components.flows.history.show');

        // Kanban List
        Route::get('/flows/{flow}/kanban/list','KanbanController@getList')->name('components.flows.kanban.list');

        // Flow Data Export
        Route::get('/flows/{flow}/export', 'ExportController@export')->name('components.flows.export');

    });
});


/**
 * User panel
 */
Route::namespace('User')->prefix('user')->middleware('auth', 'role:' . RoleName::ACCOUNTABLE_MANAGER . '|' . RoleName::COMPLIANCE_MONITORING_MANAGER . '|' . RoleName::AUDITOR . '|' . RoleName::AUDITEE . '|' . RoleName::INVESTIGATOR . '')->group(function () {

    /**
     * Flows
     */
    Route::namespace('Flows')->group(function () {

        // Flow Requirements (Table View)
        Route::get('/flows/table', 'TableController@index')->name('user.flows.table.index');
        Route::post('/flows/datatable', 'TableController@datatable')->name('user.flows.table.datatable');

        // Flow Requirements (Kanban View)
        Route::get('/flows/kanban', 'KanbanController@index')->name('user.flows.kanban.index');
        // Route::post('/flows/kanban/status', 'KanbanController@changeStatus');

    });
});


/**
 * Admin panel
 */
Route::namespace('Admin')->prefix('admin')->middleware('auth', 'role:' . RoleName::SME)->group(function () {

    /**
     * Admin homepage
     */
    Route::get('/home', 'HomeController@index')->name('admin.dashboard');

    /**
     * Users
     **/
    Route::namespace('Users')->group(function () {
        Route::resource('/users', 'UserController')->except(['create', 'show', 'destroy'])->names('admin.users');
        Route::get('/users/impersonate/login/{user_id}', 'ImpersonateController@login')->name('admin.users.impersonate.login');
        Route::get('/users/impersonate/logout/', 'ImpersonateController@logout')->name('admin.users.impersonate.logout')->withoutMiddleware('role:' . RoleName::SME);
    });

    /**
     * Companies
     **/
    Route::namespace('Companies')->group(function() {
        Route::resource('/companies', 'CompanyController')->except(['create', 'show', 'destroy'])->names('admin.companies');
        Route::post('/companies/fields/store', 'FieldController@store')->name('admin.companies.fields.store');
    });

    /**
     * Requirements
     */
    Route::namespace('Requirements')->group(function (){
        //Route::resource('/admin/requirements', 'RequirementController')->names('admin.requirements');
        Route::get('/requirements', 'RequirementController@index')->name('admin.requirements.index');
        Route::post('/requirements/store', 'RequirementController@store')->name('admin.requirements.store');
        Route::get('/requirements/{requirement}', 'RequirementController@show')->name('admin.requirements.show');
        Route::get('/requirements/history/{rule_reference}', 'RequirementController@history')->name('admin.requirements.history');
    });

    /**
     * Flows
     */
    Route::namespace('Flows')->group(function () {

        // Flows
        Route::resource('/flows', 'FlowController')->except(['create', 'show', 'destroy'])->names('admin.flows');

        // Flow Requirements (Table View)
        Route::get('/flows/{flow}/table', 'TableController@index')->name('admin.flows.table.index');
        Route::post('/flows/{flow}/table/datatable', 'TableController@datatable')->name('admin.flows.table.datatable');

        // Flow Requirements (Kanban View)
        Route::get('/flows/{flow}/kanban', 'KanbanController@index')->name('admin.flows.kanban.index');
//        Route::post('/flows/{flow}/kanban/status', 'KanbanController@changeStatus');

    });


});
