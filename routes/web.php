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

use App\Http\Controllers\Auth\Tenant\LoginController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Tenant\GoogleUsersController;
use App\Http\Controllers\Tenant\HomeController;
use App\Http\Controllers\Tenant\Web\LdapUsersController;
use App\Http\Controllers\Tenant\PendingTeachersController;
use App\Http\Controllers\Tenant\PersonalDataController;
use App\Http\Controllers\Tenant\UserPhotoController;
use App\Http\Controllers\Tenant\Web\CurriculumController;
use App\Http\Controllers\Tenant\Web\CurriculumSubjectGroupsController;
use App\Http\Controllers\Tenant\Web\CurriculumSubjectsController;
use App\Http\Controllers\Tenant\Web\IncidentsController;
use App\Http\Controllers\Tenant\Web\LoggedUserProfileController;
use App\Http\Controllers\Tenant\Web\MoodleUsersController;
use App\Http\Controllers\Tenant\Web\PermissionsController;
use App\Http\Controllers\Tenant\Web\PositionsController;
use App\Http\Controllers\Tenant\Web\PublicCurriculumController;
use App\Http\Controllers\Tenant\Web\PublicCurriculumStudiesController;
use App\Http\Controllers\Tenant\Web\RolesController;
use App\Http\Controllers\Tenant\Web\TeachersController;
use App\Http\Controllers\Tenant\Web\UsersController;
use App\Models\Module;
use App\Models\Study;
use App\Models\User;
use Illuminate\Broadcasting\BroadcastController;

use PEAR2\Net\RouterOS\Client as RouterOSClient;
use PEAR2\Net\RouterOS\Util as RouterOSUtil;
use PEAR2\Net\RouterOS\Request as RouterOSRequest;
use PEAR2\Net\RouterOS\Response as RouterOSResponse;
use PEAR2\Net\Transmitter\NetworkStream;

Route::bind('googleUser', function($value, $route)
{
    dd(intval($value));
    if (is_integer(intval($value)) && intval($value) > 0) return Module::findOrFail($value);
    if (is_string($value)) {
        return Module::where('name',$value)->firstOrFail();
    }
});

Route::bind('hashuser', function($value, $route)
{
    $hashids = new Hashids\Hashids(config('scool.salt'));
    $id = $hashids->decode($value)[0];

    return User::findOrFail($id);
});

Route::bind('studySlug', function($value, $route)
{
    return Study::all()->first(function ($study, $key) use ($value) {
        return str_slug($study->name) === $value;
    }) ?? abort(404);
});

// Allow Route Model Binding using module id or name
Route::bind('module', function($value, $route)
{
    if (is_integer(intval($value)) && intval($value) > 0) return Module::findOrFail($value);
    if (is_string($value)) {
        return Module::where('name',$value)->firstOrFail();
    }
});

// TODO ESBORRAR. Més exemples a: https://github.com/ivanvermeyen/laravel-google-drive-demo/blob/master/routes/web.php
Route::get('put', function() {
    Storage::cloud()->put('testPROVADDD.txt', 'Hello World PROVA DDD');
    return 'File was saved to Google Drive';
});

Route::domain('{tenant}.' . config('app.domain'))->group(function () {
    Route::group(['middleware' => ['tenant','tenancy.enforce']], function () {

        Route::get('/', function () {
            return view('tenants.welcome');
        });
        Route::get('/welcome', function () {
            return view('tenants.welcome');
        });

        Route::get('/pusher', function () { // TODO Borrar
            dump(config('broadcasting'));
            dump(config('broadcasting.connections')['pusher_tenant']);
        });

        // Copied from Illuminate\Broadcasting\BroadcastManager method routes - Broadcast::routes()
        Route::get('/broadcasting/auth', '\\'.BroadcastController::class.'@authenticate');
        Route::post('/broadcasting/auth', '\\'.BroadcastController::class.'@authenticate');

//        require base_path('routes/channels.php');

        // Taken from Illuminate\Routing\Router
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

        // Email verification routes
        Route::get('email/verify', 'Auth\Tenant\VerificationController@show')->name('verification.notice');
        Route::get('email/verify/{id}', 'Auth\Tenant\VerificationController@verify')->name('verification.verify');
        Route::get('email/resend', 'Auth\Tenant\VerificationController@resend')->name('verification.resend');

        // Gsuite users push notifications -> Registered with Route::post('/gsuite/users/watch', 'Tenant\GoogleUsersWatchController@store');
        Route::post('/gsuite/notifications','Tenant\GoogleUsersPushNotificationController@store');
        // TODO remove only needed for testing:
//        Route::get('/gsuite/notifications','Tenant\GoogleUsersPushNotificationController@store');

        Route::get('/add_teacher', '\\' . PendingTeachersController::class . '@showForm');
        Route::get('/new_teacher', '\\' . PendingTeachersController::class . '@showForm');
        Route::get('/nou_professor', '\\' . PendingTeachersController::class . '@showForm');

        Route::get('/pending_teacher/{teacher}', 'Tenant\PendingTeachersController@show');

        // User photos
        Route::get('/user/{hashuser}/photo','\\' . UserPhotoController::class . '@show')->name('user.photo.show');
        Route::get('/user/{hashuser}/photo/download', '\\' . UserPhotoController::class . '@download')->name('user.photo.download');

        //File upload to storage
        Route::post('file/upload/to/{storage}', 'Tenant\UploadFileToStorageController@store');
        Route::post('file/remove/from/{storage}', 'Tenant\UploadFileToStorageController@destroy');

        // Jobs Sheet
        Route::get('/jobs/sheet_active_users','Tenant\JobsSheetController@show');
        Route::get('/jobs/sheet_holders','Tenant\JobsSheetController@showHolders');

        // Public curriculum
        Route::get('/public/curriculum','\\' . PublicCurriculumController::class . '@index');
        Route::get('/public/curriculum/studies/{studySlug}','\\' . PublicCurriculumStudiesController::class . '@show');
        Route::get('/public/curriculum/estudis/{studySlug}','\\' . PublicCurriculumStudiesController::class . '@show');

        Route::get('auth/facebook', '\\'. LoginController::class . '@redirectToFacebookProvider');
        Route::get('auth/facebook/callback', '\\'. LoginController::class . '@handleFacebookProviderCallback');

        Route::group(['middleware' => 'auth'], function () {

            // Google groups
            Route::get('/google_groups','Tenant\GoogleGroupsController@show');

            // Google users
            Route::get('/google_users','\\' . GoogleUsersController::class . '@index');
            Route::get('/google_users/{googleUser}','\\' . GoogleUsersController::class . '@show');

            // Ldap users
            Route::get('/ldap_users','\\' . LdapUsersController::class . '@index');

            // ******* Emails ********
            Route::get('/mail/teacher_welcome','Tenant\TeacherWelcomeEmailController@show');

            // Personal data (people) management
            Route::get('/personal_data','\\' . PersonalDataController::class . '@show');
            Route::get('/people','\\' . PersonalDataController::class . '@show');

            // Media download
            Route::get('/media/{media}/download','Tenant\MediaController@download');


            //        Route::impersonate() but be careful about tenant!
            Route::get('/impersonate/take/{id}', function($tenant, $id) {
                return App::call('\Lab404\Impersonate\Controllers\ImpersonateController@take', ['tenant' => $tenant, 'id' => $id]);
            });
            Route::get('/impersonate/leave',
                '\Lab404\Impersonate\Controllers\ImpersonateController@leave')->name('impersonate.leave');

            Route::get('/home', '\\' . HomeController::class . '@show');


            Route::get('/users', '\\' . UsersController::class . '@index');
            Route::get('/users/roles', '\\' . RolesController::class . '@index');
            Route::get('/users/permissions', '\\' . PermissionsController::class . '@index');
            Route::get('/users/{user}','\\'.  UsersController::class . '@show');

            Route::get('/moodle/users', '\\' . MoodleUsersController::class.'@index');

            Route::get('/jobs', 'Tenant\JobsController@show');

            Route::get('/teachers', '\\' . TeachersController::class . '@index');
            Route::get('/teachers/{teacher}', '\\' . TeachersController::class . '@show');

            Route::get('/teachers_photos', 'Tenant\TeachersPhotosController@show');

            Route::get('/teacher_photo/{photo}/download', 'Tenant\TeacherPhotoController@download');
            Route::get('/teacher_photo/{photo}', 'Tenant\TeacherPhotoController@show');

            Route::post('/teacher_photo', 'Tenant\TeacherPhotoController@store');

            Route::get('/unassigned_teacher_photos/{photo}','Tenant\UnassignedTeacherPhotosController@download');
            Route::get('/unassigned_teacher_photos','Tenant\UnassignedTeacherPhotosController@downloadAll');

            Route::get('/teacher/profile','Tenant\TeacherProfileController@index');

            Route::get('/lessons','Tenant\LessonsController@show');

            Route::get('/incidents','\\'. IncidentsController::class . '@index');
            Route::get('/incidents/{incident}','\\'.  IncidentsController::class . '@show');

            Route::view('/blank','tenants.blank');

            Route::get('/settings','Tenant\SettingsController@index');

            //Changelog
            Route::get('/changelog','Tenant\Web\ChangelogController@index');
            Route::get('/changelog/module/{module}','Tenant\Web\ChangelogModuleController@index');
            Route::get('/changelog/user/{user}','Tenant\Web\ChangelogUserController@index');
            Route::get('/changelog/loggable/{loggable}/{loggableId}','Tenant\Web\ChangelogLoggableController@index');

            //Curriculum
            Route::get('/curriculum','\\' . CurriculumController::class . '@index');
            Route::get('/curriculum/subjects','\\' . CurriculumSubjectsController::class . '@index');
            Route::get('/curriculum/subjectGroups','\\' . CurriculumSubjectGroupsController::class . '@index');
            Route::get('/curriculum/subject_groups','\\' . CurriculumSubjectGroupsController::class . '@index');

            Route::get('/positions', '\\' . PositionsController::class . '@index');

            Route::get('/user/profile', '\\' . LoggedUserProfileController::class . '@show');

            Route::get('/notifications', '\\' . NotificationController::class . '@index');

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

//TODO eliminar
//Route::post('notifications', '\\' . NotificationController::class . '@store');

Route::get('/prova_ldap', function() {
    $ldapUser = Adldap::make()->user([
        'cn' => 'PEPITOPALOTES',
        'sn' => 'PALOTES'
    ]);
    if (!$ldapUser->save()) {
        dd('error');
    }
});

Route::get('/mikrotik2', function() {
    try {
        $client = new RouterOSClient(
            config('scool.routeros_ip'),
            config('scool.routeros_user'),
            config('scool.routeros_password'));

        $responses = $client->sendSync(new RouterOSRequest('/ip/arp/print'));

        foreach ($responses as $response) {
            if ($response->getType() === RouterOSResponse::TYPE_DATA) {
                echo 'IP: ', $response->getProperty('address'),
                ' MAC: ', $response->getProperty('mac-address'),
                "\n";
            }
        }
        echo 'OK';
    } catch (Exception $e) {
        die($e);
    }
});

Route::get('/arp', function() {
    $util = new RouterOSUtil(
        $client = new RouterOSClient(
            config('scool.routeros_ip'),
            config('scool.routeros_user'),
            config('scool.routeros_password'))
    );
    $util->setMenu('/ip arp');

    foreach ($util->getAll() as $item) {
        echo 'IP: ', $item->getProperty('address'),
        ' MAC: ', $item->getProperty('mac-address'),
        "</br>";
    }
});



Route::get('/dhcp', function() {
    try {
        $client = new RouterOSClient(
            config('scool.routeros_ip'),
            config('scool.routeros_user'),
            config('scool.routeros_password'));

        $responses = $client->sendSync(new RouterOSRequest('/ip/arp/print'));

        foreach ($responses as $response) {
            if ($response->getType() === RouterOSResponse::TYPE_DATA) {
                echo 'IP: ', $response->getProperty('address'),
                ' MAC: ', $response->getProperty('mac-address'),
                "\n";
            }
        }
        echo 'OK';
    } catch (Exception $e) {
        die($e);
    }
});
