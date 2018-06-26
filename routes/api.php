<?php

use Illuminate\Http\Request;

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

Route::domain('{tenant}.' . env('APP_DOMAIN'))->group(function () {
    Route::group(['middleware' => ['tenant','tenancy.enforce']], function () {
        Route::group(['prefix' => 'v1','middleware' => 'auth:api'], function () {

            //Subject lesson calculator
            Route::post('lessons/subject/{subject}/calculate', 'Tenant\SubjectLessonsCalculateController@store');

            //Available users
            Route::get('/available-users/{jobType?}', 'Tenant\AvailableUsersController@index');

            // Job substitutions
            Route::put('/job/{job}/substitution', 'Tenant\JobSubstitutionsController@update');
            Route::post('/job/{job}/substitution', 'Tenant\JobSubstitutionsController@store');
            Route::delete('/job/{job}/substitution/{user}', 'Tenant\JobSubstitutionsController@destroy');
            Route::delete('/job/{job}/substitutions', 'Tenant\JobSubstitutionsController@destroyAll');

            //Propose free user names
            Route::get('/proposeFreeUserName/{name}/{sn1}', 'Tenant\ProposeFreeUsernameController@index');

            // Teachers
            Route::get('/teachers', 'Tenant\TeachersController@index');
            Route::post('/teachers', 'Tenant\TeachersController@store');
            Route::delete('/teachers/{teacher}', 'Tenant\TeachersController@destroy');

            // Finish teacher add
            Route::post('/teacher/finish_add', 'Tenant\TeacherFinishAddController@store');

            // Approved teachers
            Route::post('/approved_teacher', 'Tenant\ApprovedTeacherController@store');
            Route::delete('/approved_teacher/{user}', 'Tenant\ApprovedTeacherController@destroy');

            // Logged user teacher
            Route::get('/teacher', 'Tenant\LoggedUserTeacherController@show');


            //Available teacher code:
            Route::get('/teacher/available_code', 'Tenant\TeacherAvailableCodeController@show');

            // User Person
            Route::post('/user_person', 'Tenant\UserPersonController@store');
            Route::delete('/user_person/{user}', 'Tenant\UserPersonController@destroy');

            // USERS
            Route::put('/user', 'Tenant\LoggedUserController@update');
            Route::get('/users', 'Tenant\UsersController@index');
            Route::post('/users', 'Tenant\UsersController@store');
            Route::delete('/users/{user}', 'Tenant\UsersController@destroy');

            // Available users
            Route::get('/users/available', 'Tenant\UsersAvailableController@index');

            //Pending teachers
            Route::get('/pending_teachers', 'Tenant\PendingTeachersController@index');
            Route::delete('/pending_teacher/{teacher}', 'Tenant\PendingTeachersController@destroy');

            //Teacher photos
            Route::post('/teachers_photos', 'Tenant\TeachersPhotosController@store');

            Route::get('/unassigned_teacher_photo', 'Tenant\UnassignedTeacherPhotoController@index');
            Route::post('/unassigned_teacher_photo', 'Tenant\UnassignedTeacherPhotoController@store');

            Route::delete('/unassigned_teacher_photo/{photoslug}', 'Tenant\UnassignedTeacherPhotoController@destroy');
            Route::post('/unassigned_teacher_photos', 'Tenant\UnassignedTeacherPhotosController@store');
            Route::delete('/unassigned_teacher_photos/{photoslug}', 'Tenant\UnassignedTeacherPhotosController@destroy');
            Route::delete('/unassigned_teacher_photos', 'Tenant\UnassignedTeacherPhotosController@destroyAll');

            Route::put('/teacher_photo/{photoslug}', 'Tenant\TeacherPhotoController@edit');

            //Assign available teacher photo to teacher
            Route::post('/teacher/{user}/photo','Tenant\AssignedTeacherPhotoController@store');
            //Assign al available teacher photos to teachers automatically
            Route::post('/teachers/photos','Tenant\AssignedTeacherPhotoController@storeAll');

            //UnAssign available teacher photo to teacher
            Route::delete('/teacher/{user}/photo','Tenant\AssignedTeacherPhotoController@delete');

            //User photos
            Route::post('/user/{user}/photo','Tenant\UserPhotoController@store');

            //Jobs
            Route::get('/jobs', 'Tenant\JobsController@index');
            Route::post('/jobs', 'Tenant\JobsController@store');
            Route::put('/jobs/{job}', 'Tenant\JobsController@update');
            Route::delete('/jobs/{job}', 'Tenant\JobsController@destroy');
            Route::get('/jobs/nextAvailableCode', 'Tenant\JobsController@nextAvailableCode');

            //Employee
            Route::post('/employee', 'Tenant\EmployeeController@store');
            Route::delete('/employee/{employee}', 'Tenant\EmployeeController@destroy');

            //Google GSuite users
            Route::get('/gsuite/users', 'Tenant\GoogleSuiteUsersController@index');
            Route::get('/gsuite/users/{email}', 'Tenant\GoogleSuiteUsersController@show');
            Route::post('/gsuite/users', 'Tenant\GoogleSuiteUsersController@store');
            Route::delete('/gsuite/users/{email}', 'Tenant\GoogleSuiteUsersController@destroy');

            // Google Suite deleted users
            // Undelete user
            Route::post('/gsuite/deleted_users/{email}', 'Tenant\GoogleSuiteDeletedUsersController@undelete');
            Route::get('/gsuite/deleted_users/{email}', 'Tenant\GoogleSuiteDeletedUsersController@show');

            //Google Suite watch users
            Route::post('/gsuite/watchusers', 'Tenant\GoogleSuiteWatchUsersController@store');

        });

        Route::group(['prefix' => 'v1'], function () {
            Route::get('/menu', 'Tenant\MenuController@index');

            Route::post('/add_teacher', 'Tenant\PendingTeachersController@store');

            Route::get('/provinces','Tenant\ProvincesController@index');
            Route::get('/localities','Tenant\LocalitiesController@index');
            Route::get('/locations','Tenant\LocalitiesController@index');

            Route::get('/gsuite/test_connection','Tenant\GoogleSuiteTestConnectionController@index');
        });
    });
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'v1','middleware' => 'auth:api'], function () {
    Route::get('tenant','UserTenantController@index');
    Route::post('tenant','UserTenantController@store');
    Route::delete('tenant/{tenant}','UserTenantController@destroy');
    Route::put('tenant/{tenant}/name','UserTenantNameController@update');
    Route::put('tenant/{tenant}/subdomain','UserTenantSubdomainController@update');
    Route::put('tenant/{tenant}/password','UserTenantPasswordController@update');

    Route::get('tenant/{tenant}/test','UserTenantTestController@index');
    Route::post('tenant/{tenant}/test-user','UserTenantTestAdminUserController@index');

});

