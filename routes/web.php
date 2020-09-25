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
Route::get('/', 'Auth\LoginController@showLoginForm');

/**
 * login microsoft azure ad
 */
Route::get('/login/azure', 'Auth\LoginController@redirectToProvider')->name('azure.login');
Route::get('/login/azure/callback', 'Auth\LoginController@handleProviderCallback');

/**
 * MS TEAMS
 */
Route::get('/login/teams', 'Auth\TeamController@login')->name('auth.login.teams');
Route::get('/login/teams-start', 'Auth\TeamController@start')->name('auth.login.teams_start');
Route::get('/login/teams-end', 'Auth\TeamController@end')->name('auth.login.teams_end');
Route::get('/login/teams/profile/{email}/name/{displayName?}', 'Auth\TeamController@profile')->name('auth.login.profile');


/**
 * Components
 */
Route::namespace('Components')->group(function () {

    /**
     * Flow Requirements
     */
    Route::namespace('Flows')->group(function () {
        // Flow Filter
        Route::get('/flows/{flow?}/filter/show', 'FilterController@showFilterForm')->name('components.flows.filters.show');
        Route::post('/flows/{flow}/filter/store', 'FilterController@store')->name('components.flows.filters.store');
        Route::post('/flows/{flow?}/filter/search', 'FilterController@search')->name('components.flows.filters.search');

        // Flow Calendar
        Route::get('/calendar/{hash}', 'CalendarController@index')->name('components.flows.calendar.index');

    });
});


/*
 * Auth
 */
Auth::routes(['register' => false]);
//Auth::routes();

/**
 * User panel
 */
Route::namespace('User')->prefix('user')->middleware('auth', 'role:' . RoleName::ACCOUNTABLE_MANAGER . '|' . RoleName::COMPLIANCE_MONITORING_MANAGER . '|' . RoleName::AUDITOR . '|' . RoleName::AUDITEE . '|' . RoleName::INVESTIGATOR . '')->group(function () {

    /**
     * User homepage
     */
    Route::get('/home', 'HomeController@index')->name('user.dashboard');

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

        // Flow Requirements (Edit Form)
        Route::get('/flows/requirements/{rule_reference}/edit', 'RequirementController@edit')->name('user.flows.requirements.edit'); // ToDo user.flows.requirements.edit
        Route::post('/flows/requirements/', 'RequirementController@update')->name('user.flows.requirements.update'); // ToDO user.flows.requirements.update


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
    Route::resource('/users', 'UserController')->except(['create', 'show', 'destroy'])->names('admin.users');
    Route::get('/users/impersonate/login/{user_id}', 'Users\ImpersonateController@login')->name('admin.users.impersonate.login');
    Route::get('/users/impersonate/logout/', 'Users\ImpersonateController@logout')->name('admin.users.impersonate.logout')->withoutMiddleware('role:' . RoleName::SME);

    /**
     * Companies
     **/
    Route::resource('/companies', 'CompanyController')->except(['create', 'show', 'destroy'])->names('admin.companies');

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
        Route::resource('/flows', 'FlowController')->except(['create', 'show', 'destroy'])->names('admin.flows');

        // Flow Requirements (Table View)
        Route::get('/flows/{flow}/table', 'TableController@index')->name('admin.flows.table.index');
        Route::post('/flows/{flow}/table/datatable', 'TableController@datatable')->name('admin.flows.table.datatable');

        // Flow Requirements (Kanban View)
        Route::get('/flows/{flow}/kanban', 'KanbanController@index')->name('admin.flows.kanban.index');
//        Route::post('/flows/{flow}/kanban/status', 'KanbanController@changeStatus');

        // Flow Requirements (Edit Form)
        Route::get('/flows/{flow}/requirements/{rule_reference}/edit', 'RequirementController@edit')->name('admin.flows.requirements.edit');
        Route::post('/flows/{flow}/requirements', 'RequirementController@update')->name('admin.flows.requirements.update');

    });


});
