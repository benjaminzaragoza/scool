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

use App\Models\User;

Route::bind('hashuser', function($value, $route)
{
    $hashids = new Hashids\Hashids(config('scool.salt'));
    $id = $hashids->decode($value)[0];

    return User::findOrFail($id);
});

//dump(config('app.domain'));

Route::domain('{tenant}.' . config('app.domain'))->group(function () {
    Route::group(['middleware' => ['tenant','tenancy.enforce']], function () {
        // Authentication Routes...
        Route::get('login', 'Auth\Tenant\LoginController@showLoginForm')->name('login');
        Route::post('login', 'Auth\Tenant\LoginController@login');
        Route::post('logout', 'Auth\Tenant\LoginController@logout')->name('logout');

        // Registration Routes...
        Route::get('register', 'Auth\Tenant\RegisterController@showRegistrationForm')->name('register');
        Route::post('register', 'Auth\Tenant\RegisterController@register');

        // Password Reset Routes...
        Route::get('password/reset', 'Auth\Tenant\ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('password/email', 'Auth\Tenant\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('password/reset/{token}', 'Auth\Tenant\ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('password/reset', 'Auth\Tenant\ResetPasswordController@reset');

        Route::get('/', function () {
            return view('tenants.welcome');
        });

        // Gsuite users push notifications
        Route::post('/gsuite/notifications','Tenant\GoogleSuiteUsersPushNotificationController@store');

        Route::get('/add_teacher', 'Tenant\PendingTeachersController@showForm');
        Route::get('/nou_professor', 'Tenant\PendingTeachersController@showForm');

        Route::get('/pending_teacher/{teacher}', 'Tenant\PendingTeachersController@show');

        // User photos
        Route::get('/user/{hashuser}/photo','Tenant\UserPhotoController@show')->name('user.photo.show');
        Route::get('/user/{hashuser}/photo/download', 'Tenant\UserPhotoController@download')->name('user.photo.download');

        //File upload to storage
        Route::post('file/upload/to/{storage}', 'Tenant\UploadFileToStorageController@store');
        Route::post('file/remove/from/{storage}', 'Tenant\UploadFileToStorageController@destroy');

        // Jobs Sheet
        Route::get('/jobs/sheet_active_users','Tenant\JobsSheetController@show');
        Route::get('/jobs/sheet_holders','Tenant\JobsSheetController@showHolders');

        Route::group(['middleware' => 'auth'], function () {

            // Personal data (people) management
            Route::get('/personal_data','Tenant\PersonalDataController@show');

            // Media download
            Route::get('/media/{media}/download','Tenant\MediaController@download');


            //        Route::impersonate() but be careful about tenant!
            Route::get('/impersonate/take/{id}', function($tenant, $id) {
                return App::call('\Lab404\Impersonate\Controllers\ImpersonateController@take', ['tenant' => $tenant, 'id' => $id]);
            });
            Route::get('/impersonate/leave',
                '\Lab404\Impersonate\Controllers\ImpersonateController@leave')->name('impersonate.leave');

            Route::get('/home', 'Tenant\HomeController@show');

            Route::get('/users', 'Tenant\UsersController@show');

            Route::get('/jobs', 'Tenant\JobsController@show');

            Route::get('/teachers', 'Tenant\TeachersController@show');

            Route::get('/teachers_photos', 'Tenant\TeachersPhotosController@show');

            Route::get('/teacher_photo/{photo}/download', 'Tenant\TeacherPhotoController@download');
            Route::get('/teacher_photo/{photo}', 'Tenant\TeacherPhotoController@show');

            Route::post('/teacher_photo', 'Tenant\TeacherPhotoController@store');

            Route::get('/unassigned_teacher_photos/{photo}','Tenant\UnassignedTeacherPhotosController@download');
            Route::get('/unassigned_teacher_photos','Tenant\UnassignedTeacherPhotosController@downloadAll');

            Route::get('/teacher/profile','Tenant\TeacherProfileController@index');

            Route::get('/lessons','Tenant\LessonsController@show');

        });
    });

    // TEST TODO ESBORRAR!
    Route::group(['middleware' => 'tenant'], function () {
        Route::get('test', function ($tenant) {
            return view('tenants.test',['tenant' => get_tenant($tenant)]);
        });
    });

    Route::get('/test_controller', 'TenantTestController@index');

});

Route::get('/', function () {
    return view('welcome');
});

Route::auth();

Route::get('/home', 'HomeController@index');

// Push Subscriptions
Route::post('subscriptions', 'PushSubscriptionController@update');
Route::post('subscriptions/delete', 'PushSubscriptionController@destroy');

Route::get('push', 'PushController@index');
Route::post('notifications', 'NotificationController@store');
