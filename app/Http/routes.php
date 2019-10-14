<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/* Route::get('/', function () {
    return view('la/dashboard');
});*/
Route::get('/', 'LA\DashboardController@index')->name('dashboard');
/* ================== Homepage + Admin Routes ================== */


/* ================== Homepage ================== */
/* Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index');
Route::auth();
   */
 
Route::get('mail/queue', function() {
    Mail::later('1', 'emails.queued_email', ["name" => "vivek"], function($message)
    {
        $message->to('vivekt@nltechdev.com', 'eas')->subject('Welcome!');
    });
    return 'Email will be sent in 1 seconds';
});
Route::get('dashboard', 'LA\DashboardController@index')->name('dashboard');
//Route::get('/', 'Auth\AuthController@showLoginForm');
Route::get('/dashboard', 'Auth\Dashboard@index');
Route::get('/home', 'HomeController@index');
Route::auth();
 
Route::get(config('laraadmin.adminRoute'). '/public', 'LA\DashboardController@index');
/* ================== Access Uploaded Files ================== */
Route::get('files/{hash}/{name}', 'LA\UploadsController@get_file');

/* ================== Access Employee Error Logs Files ================== */
Route::get('logs/{name}', 'LA\EmployeesController@get_file');
/* ================== Access Employee Template  Files ================== */
Route::get('templates/{name}', 'LA\DashboardController@get_file');

/*
|--------------------------------------------------------------------------
| Admin Application Routes
|--------------------------------------------------------------------------
*/

$as = "";
if(\Dwij\Laraadmin\Helpers\LAHelper::laravel_ver() == 5.3) {
	$as = config('laraadmin.adminRoute').'.';
	
	// Routes for Laravel 5.3
	Route::get('/logout', 'Auth\LoginController@logout');
}

Route::group(['as' => $as, 'middleware' => ['auth', 'permission:ADMIN_PANEL']], function () {
    
	/* ================== Dashboard ================== */
    Route::get('dashboard' , 'LA\DashboardController@index');
	 
	//Route::get('/dashboard.index' , 'LA\DashboardController@index');
	Route::get(config('laraadmin.adminRoute'). '/dashboard', 'LA\DashboardController@index');
	Route::get(config('laraadmin.adminRoute'). '/test', 'LA\DashboardController@test');
	
	/* ================== Users ================== */
	Route::resource(config('laraadmin.adminRoute') . '/users', 'LA\UsersController');
	Route::get(config('laraadmin.adminRoute') . '/user_dt_ajax', 'LA\UsersController@dtajax');

	
	/* ================== Roles ================== */
	Route::resource(config('laraadmin.adminRoute') . '/roles', 'LA\RolesController');
	Route::get(config('laraadmin.adminRoute') . '/role_dt_ajax', 'LA\RolesController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/save_module_role_permissions/{id}', 'LA\RolesController@save_module_role_permissions');
	
	/* ================== Permissions ================== */
	Route::resource(config('laraadmin.adminRoute') . '/permissions', 'LA\PermissionsController');
	Route::get(config('laraadmin.adminRoute') . '/permission_dt_ajax', 'LA\PermissionsController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/save_permissions/{id}', 'LA\PermissionsController@save_permissions');
	
	/* ================== Departments ================== */
	Route::resource(config('laraadmin.adminRoute') . '/departments', 'LA\DepartmentsController');
	Route::get(config('laraadmin.adminRoute') . '/department_dt_ajax', 'LA\DepartmentsController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/department_file_validation_dt_ajax', 'LA\DepartmentsController@fileValidationAjax');
	
	/* ================== Employees ================== */
	Route::resource(config('laraadmin.adminRoute') . '/employees', 'LA\EmployeesController');
	Route::get(config('laraadmin.adminRoute') . '/employee_dt_ajax/{id}', 'LA\EmployeesController@dtajax');
	Route::get('/employee_dt_ajax', 'LA\EmployeesController@dtajax');
 	Route::post(config('laraadmin.adminRoute') . '/change_password/{id}', 'LA\EmployeesController@change_password');
	Route::post(config('laraadmin.adminRoute') . '/add_employee_performance_ajax', 'LA\EmployeesController@addPerformanceAjax');
	Route::resource(config('laraadmin.adminRoute') . '/myprofile', 'LA\EmployeesController\myprofile');
	Route::post(config('laraadmin.adminRoute') . '/employee_upload_files', 'LA\EmployeesController@upload_files');
	
	/* ================== Organizations ================== */
	Route::resource(config('laraadmin.adminRoute') . '/organizations', 'LA\OrganizationsController');
	Route::get(config('laraadmin.adminRoute') . '/organization_dt_ajax', 'LA\OrganizationsController@dtajax');

	/* ================== Backups ================== */
	Route::resource(config('laraadmin.adminRoute') . '/backups', 'LA\BackupsController');
	Route::get(config('laraadmin.adminRoute') . '/backup_dt_ajax', 'LA\BackupsController@dtajax');
	Route::post(config('laraadmin.adminRoute') . '/create_backup_ajax', 'LA\BackupsController@create_backup_ajax');
	Route::get(config('laraadmin.adminRoute') . '/downloadBackup/{id}', 'LA\BackupsController@downloadBackup');
	/* ================== Performance_Appraisals ================== */
	Route::resource( '/performance_appraisals', 'LA\Performance_AppraisalsController');
	Route::get(  '/performance_appraisal_dt_ajax/{id}', 'LA\Performance_AppraisalsController@dtajax');
	Route::get(  '/performance_appraisal_dt_ajax', 'LA\Performance_AppraisalsController@dtajax');
	Route::post ( '/performance_appraisal_update_status_ajax', 'LA\Performance_AppraisalsController@updateStatusAjax');
	/* ================== Ratings ================== */
	Route::resource(config('laraadmin.adminRoute') . '/ratings', 'LA\RatingsController');
	Route::get(config('laraadmin.adminRoute') . '/rating_dt_ajax', 'LA\RatingsController@dtajax');
	
	
	/* ================== Evaluation_Periods ================== */
	Route::resource(config('laraadmin.adminRoute') . '/evaluation_periods', 'LA\Evaluation_PeriodsController');
	Route::get(config('laraadmin.adminRoute') . '/evaluation_period_dt_ajax', 'LA\Evaluation_PeriodsController@dtajax');
	
	/* ================== Goals ================== */
	Route::resource(config('laraadmin.adminRoute') . '/goals', 'LA\GoalsController');
	Route::get(config('laraadmin.adminRoute') . '/goal_dt_ajax', 'LA\GoalsController@dtajax');
	
	
	/* ================== Appraisal_Templates ================== */
	Route::resource(config('laraadmin.adminRoute') . '/appraisal_templates', 'LA\Appraisal_TemplatesController');
	Route::get(config('laraadmin.adminRoute') . '/appraisal_template_dt_ajax', 'LA\Appraisal_TemplatesController@dtajax');
	
	/* ================== Uploads ================== */
	Route::resource(config('laraadmin.adminRoute') . '/uploads', 'LA\UploadsController');
	Route::post(config('laraadmin.adminRoute') . '/upload_files', 'LA\UploadsController@upload_files');
	Route::get(config('laraadmin.adminRoute') . '/uploaded_files', 'LA\UploadsController@uploaded_files');
	Route::post(config('laraadmin.adminRoute') . '/uploads_update_caption', 'LA\UploadsController@update_caption');
	Route::post(config('laraadmin.adminRoute') . '/uploads_update_filename', 'LA\UploadsController@update_filename');
	Route::post(config('laraadmin.adminRoute') . '/uploads_update_public', 'LA\UploadsController@update_public');
	Route::post(config('laraadmin.adminRoute') . '/uploads_delete_file', 'LA\UploadsController@delete_file');
	
	
	/*	 ================== Reports ==================  */
	Route::resource('/reports/ratings', 'LA\ReportsController@ratings');
	Route::resource('/reports', 'LA\ReportsController@index');
	Route::get(  '/reports_performance_appraisal_dt_ajax/{id}', 'LA\ReportsController@dtajax');
	Route::get(  '/reports_performance_appraisal_dt_ajax', 'LA\ReportsController@dtajax');
	Route::get(  '/reports_performance_appraisal_dt_ratings/{id}', 'LA\ReportsController@dtratings');
	Route::get(  '/reports_performance_appraisal_dt_ratings', 'LA\ReportsController@dtratings');
});

 
 