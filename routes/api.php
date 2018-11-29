<?php

use App\Http\Controllers\Tenant\Api\Moodle\Users\MoodleUsersController;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\RoleDoesNotExist;
use Spatie\Permission\Models\Role;
use App\Models\Log as Changelog;
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

// Allow Route Model Binding using role id or name
Route::bind('role', function($value, $route)
{
    if (is_integer(intval($value)) && intval($value) > 0) return Role::findOrFail($value);
    if (is_string($value)) {
        return Role::where('name',$value)->firstOrFail();
    }
    throw new RoleDoesNotExist(var_export($value,true));
});

Route::bind('loggable', function($value, $route)
{
    if (array_key_exists('loggableId',$route->parameters)) {
        if ($loggableClass = Changelog::getLoggableByApiURI($value)) {
            return $loggableClass::findOrFail($route->parameters['loggableId']);
        }
    }
    abort(404,"No s'ha trobat cap recurs amb aquesta classe o id");
});

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
            Route::get('/users/{user}', 'Tenant\UsersController@get');

            // Moodle Users
            Route::get('/moodle/users', '\\'. MoodleUsersController::class .'@index');
            Route::delete('/moodle/users/{moodleuser}', '\\'. MoodleUsersController::class .'@destroy');

            //GET USER BY EMAIL
            Route::get('/users/email/{email}', 'Tenant\UserEmailsController@get');

            //GET USER BY name
            Route::get('/users/name/{name}', 'Tenant\UserNamesController@get');

            // Available users
            // TODO: UMMMMM
//            Route::get('/users/available', 'Tenant\UsersAvailableController@index');

            // UserRoles
            Route::post('/user/{user}/role/{role}','Tenant\Api\Roles\UserRoleController@store');
            Route::delete('/user/{user}/role/{role}','Tenant\Api\Roles\UserRoleController@destroy');

            //RoleUsers
            Route::get('/role/{role}/users','Tenant\Api\Roles\RoleUsersController@index');

            //RoleByName
            Route::get('/role/name/{name}','Tenant\Api\Roles\RoleNameController@show');
//            Route::get('/role/name','Tenant\Api\Roles\RoleName@store');

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
            Route::delete('/user/{user}/photo','Tenant\UserPhotoController@destroy');

            //Jobs
            Route::get('/jobs', 'Tenant\JobsController@index');
            Route::post('/jobs', 'Tenant\JobsController@store');
            Route::put('/jobs/{job}', 'Tenant\JobsController@update');
            Route::delete('/jobs/{job}', 'Tenant\JobsController@destroy');
            Route::get('/jobs/nextAvailableCode', 'Tenant\JobsController@nextAvailableCode');

            //Employee
            Route::post('/employee', 'Tenant\EmployeeController@store');
            Route::delete('/employee/{employee}', 'Tenant\EmployeeController@destroy');

            // Google Apps/Gsuite groups
            Route::get('/gsuite/groups', 'Tenant\GoogleGroupsController@index');
            Route::post('/gsuite/groups', 'Tenant\GoogleGroupsController@store');
            Route::delete('/gsuite/groups/{group}', 'Tenant\GoogleGroupsController@destroy');

            // Group members
            Route::get('/gsuite/groups/{group}/members', 'Tenant\GoogleGroupMembersController@index');

            //Google Apps/GSuite users
            Route::get('/gsuite/users', 'Tenant\GoogleUsersController@index');
            Route::post('/gsuite/users', 'Tenant\GoogleUsersController@store');
            Route::delete('/gsuite/users/{user}', 'Tenant\GoogleUsersController@destroy');

            //Associate Gsuite user to user
            Route::post('/user/{user}/gsuite','Tenant\UserGsuiteController@store');
            Route::put('/user/{user}/gsuite','Tenant\UserGsuiteController@edit');
            Route::delete('/user/{user}/gsuite','Tenant\UserGsuiteController@destroy');

            Route::post('/gsuite/users/search','Tenant\GoogleUsersSearchController@search');


            //Google Ldap users
            Route::get('/ldap/users', 'Tenant\LdapUsersController@index');
            Route::post('/ldap/users', 'Tenant\LdapUsersController@store');
//            Route::delete('/ldap/users/{user}', 'Tenant\LdapUsersController@destroy');

            //Google Suite watch users TODO
            Route::post('/gsuite/users/watch', 'Tenant\GoogleUsersWatchController@store');

            //Resend user email verification email
            Route::get('/email/resend/{user}', 'Auth\Tenant\VerificationController@resendUser');

            //Re(send) welcome user email
            Route::get('/email/welcome/{user}', 'Auth\Tenant\ForgotPasswordController@welcome');

            // IncidentTags
            Route::get('/incidents/tags','Tenant\Api\Incidents\IncidentTagsController@index');
            Route::get('/incidents/tags/{tag}','Tenant\Api\Incidents\IncidentTagsController@show');
            Route::post('/incidents/tags','Tenant\Api\Incidents\IncidentTagsController@store');
            Route::put('/incidents/tags/{tag}','Tenant\Api\Incidents\IncidentTagsController@update');
            Route::delete('/incidents/tags/{tag}','Tenant\Api\Incidents\IncidentTagsController@destroy');

            //Tagged incidents
            Route::post('/incidents/{incident}/tags/{tag}','Tenant\Api\Incidents\TaggedIncidentsController@store');
            Route::delete('/incidents/{incident}/tags/{tag}','Tenant\Api\Incidents\TaggedIncidentsController@destroy');

            //Incident assignees
            Route::post('/incidents/{incident}/assignees/{user}','Tenant\Api\Incidents\IncidentAssigneesController@store');
            Route::delete('/incidents/{incident}/assignees/{user}','Tenant\Api\Incidents\IncidentAssigneesController@destroy');

                // INCIDENTS
            Route::get('/incidents', 'Tenant\Api\Incidents\IncidentsController@index');
            Route::post('/incidents', 'Tenant\Api\Incidents\IncidentsController@store');
            Route::get('/incidents/{incident}', 'Tenant\Api\Incidents\IncidentsController@show');
            Route::delete('/incidents/{incident}', 'Tenant\Api\Incidents\IncidentsController@destroy');

            //Closed incidents
            Route::post('/closed_incidents/{incident}', 'Tenant\Api\Incidents\IncidentsClosedController@store');
            Route::delete('/closed_incidents/{incident}', 'Tenant\Api\Incidents\IncidentsClosedController@destroy');

            //Incidents individual fields
            Route::put('/incidents/{incident}/subject','Tenant\Api\Incidents\IncidentsSubjectController@update');
            Route::put('/incidents/{incident}/description','Tenant\Api\Incidents\IncidentsDescriptionController@update');

            // Incident Replies
            Route::get('/incidents/{incident}/replies','Tenant\Api\Incidents\IncidentRepliesController@index');
            Route::post('/incidents/{incident}/replies','Tenant\Api\Incidents\IncidentRepliesController@store');
            Route::put('/incidents/{incident}/replies/{reply}','Tenant\Api\Incidents\IncidentRepliesController@update');
            Route::delete('/incidents/{incident}/replies/{reply}','Tenant\Api\Incidents\IncidentRepliesController@destroy');

            //BodyReplies
            Route::put('/replies/{reply}/body','Tenant\Api\Incidents\RepliesBodyController@update');

            //Settings
            Route::put('/settings/{setting}','Tenant\Api\Settings\SettingsController@update');

            Route::get('/settings/filter/{module}','Tenant\Api\Settings\FilteredSettingsController@index');
            Route::put('/settings/filter/{module}','Tenant\Api\Settings\FilteredSettingsController@update');

            //Changelog
            Route::get('/changelog','Tenant\Api\Changelog\ChangelogController@index');
            Route::get('/changelog/module/{module}','Tenant\Api\Changelog\ChangelogModuleController@index');
            Route::get('/changelog/user/{user}','Tenant\Api\Changelog\ChangelogUserController@index');
            Route::get('/changelog/loggable/{loggable}/{loggableId}','Tenant\Api\Changelog\ChangelogLoggableController@index');
        });

        Route::group(['prefix' => 'v1'], function () {
            Route::get('/menu', 'Tenant\MenuController@index');

            Route::post('/add_teacher', 'Tenant\PendingTeachersController@store');

            Route::get('/provinces','Tenant\ProvincesController@index');
            Route::get('/localities','Tenant\LocalitiesController@index');
            Route::get('/locations','Tenant\LocalitiesController@index');

            // TODO remove
//            Route::get('/gsuite/test_connection','Tenant\GoogleSuiteTestConnectionController@index');
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

