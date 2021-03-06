<?php

use App\Http\Controllers\Auth\Tenant\VerificationController;
use App\Http\Controllers\Tenant\Api\Curriculum\Studies\StudiesCodeController;
use App\Http\Controllers\Tenant\Api\Curriculum\Studies\StudiesController;
use App\Http\Controllers\Tenant\Api\Curriculum\Studies\StudiesNameController;
use App\Http\Controllers\Tenant\Api\Curriculum\Studies\StudiesShortnameController;
use App\Http\Controllers\Tenant\Api\Curriculum\Studies\StudiesSubjectGroupsNumberController;
use App\Http\Controllers\Tenant\Api\Curriculum\Studies\StudyDepartmentController;
use App\Http\Controllers\Tenant\Api\Curriculum\Studies\StudyFamilyController;
use App\Http\Controllers\Tenant\Api\Curriculum\Studies\StudyTagsController;
use App\Http\Controllers\Tenant\Api\Curriculum\Studies\TaggedStudiesController;
use App\Http\Controllers\Tenant\Api\Curriculum\SubjectGroups\SubjectGroupsCodeController;
use App\Http\Controllers\Tenant\Api\Curriculum\SubjectGroups\SubjectGroupsController;
use App\Http\Controllers\Tenant\Api\Curriculum\SubjectGroups\SubjectGroupsNameController;
use App\Http\Controllers\Tenant\Api\Curriculum\SubjectGroups\SubjectGroupsShortnameController;
use App\Http\Controllers\Tenant\Api\Curriculum\SubjectGroups\SubjectGroupsTagsController;
use App\Http\Controllers\Tenant\Api\Curriculum\SubjectGroups\SubjectGroupSubjectsNumberController;
use App\Http\Controllers\Tenant\Api\Curriculum\SubjectGroups\TaggedSubjectGroupsController;
use App\Http\Controllers\Tenant\Api\Curriculum\Subjects\SubjectsCodeController;
use App\Http\Controllers\Tenant\Api\Curriculum\Subjects\SubjectsController;
use App\Http\Controllers\Tenant\Api\Curriculum\Subjects\SubjectsNameController;
use App\Http\Controllers\Tenant\Api\Curriculum\Subjects\SubjectsShortnameController;
use App\Http\Controllers\Tenant\Api\Git\GitController;
use App\Http\Controllers\Tenant\Api\Google\GoogleUsersController;
use App\Http\Controllers\Tenant\Api\Google\GoogleUsersPasswordController;
use App\Http\Controllers\Tenant\Api\Ldap\Users\LdapUsersController;
use App\Http\Controllers\Tenant\Api\Ldap\Users\LdapUsersPasswordController;
use App\Http\Controllers\Tenant\Api\Ldap\Users\UserLdapController;
use App\Http\Controllers\Tenant\Api\Moodle\Users\MoodleUsersCheckController;
use App\Http\Controllers\Tenant\Api\Moodle\Users\MoodleUsersController;
use App\Http\Controllers\Tenant\Api\Moodle\Users\MoodleUsersPasswordController;
use App\Http\Controllers\Tenant\Api\Moodle\Users\UserMoodleController;
use App\Http\Controllers\Tenant\Api\Notifications\NotificationsController;
use App\Http\Controllers\Tenant\Api\Notifications\SimpleNotificationsController;
use App\Http\Controllers\Tenant\Api\Notifications\UserNotificationsController;
use App\Http\Controllers\Tenant\Api\Notifications\UserUnreadNotificationsController;
use App\Http\Controllers\Tenant\Api\Permissions\PermissionsController;
use App\Http\Controllers\Tenant\Api\Person\Identifiers\CheckIdentifierController;
use App\Http\Controllers\Tenant\Api\Person\IdentifierTypes\IdentifierTypesController;
use App\Http\Controllers\Tenant\Api\Person\PeopleController;
use App\Http\Controllers\Tenant\Api\Person\UserPeopleController;
use App\Http\Controllers\Tenant\Api\Positions\PositionsCodeController;
use App\Http\Controllers\Tenant\Api\Positions\PositionsController;
use App\Http\Controllers\Tenant\Api\Positions\PositionsNameController;
use App\Http\Controllers\Tenant\Api\Positions\PositionsSendPositionAssignedEmailController;
use App\Http\Controllers\Tenant\Api\Positions\PositionsShortnameController;
use App\Http\Controllers\Tenant\Api\Positions\PositionUsersController;
use App\Http\Controllers\Tenant\Api\Roles\RolesController;
use App\Http\Controllers\Tenant\Api\Roles\UserRoleController;
use App\Http\Controllers\Tenant\Api\Teachers\TeachersController;
use App\Http\Controllers\Tenant\Api\Users\LoggedUserController;
use App\Http\Controllers\Tenant\Api\Users\OnlineUsersController;
use App\Http\Controllers\Tenant\Api\Users\Password\UserPasswordController;
use App\Http\Controllers\Tenant\Api\Users\UserEmailsController;
use App\Http\Controllers\Tenant\Api\Users\UserMobileController;
use App\Http\Controllers\Tenant\Api\Users\UserNamesController;
use App\Http\Controllers\Tenant\Api\Users\UserPersonController;
use App\Http\Controllers\Tenant\Api\Users\UsersController;
use App\Http\Controllers\Tenant\Api\UserType\UserTypeController;
use App\Http\Controllers\Tenant\GoogleUsersSearchController;
use App\Http\Controllers\Tenant\UserGsuiteController;
use App\Http\Controllers\Tenant\UserPhotoController;
use Illuminate\Http\Request;
use Laravel\Dusk\Http\Controllers\UserController;
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
            Route::get('/teachers', '\\' . TeachersController::class . '@index');
            Route::post('/teachers', '\\' . TeachersController::class . '@store');
            Route::delete('/teachers/{teacher}', 'Tenant\TeachersController@destroy');
            Route::delete('/teachers/{teacher}', '\\' . TeachersController::class . '@destroy');

            // Finish teacher add
            Route::post('/teacher/finish_add', 'Tenant\TeacherFinishAddController@store');

            // Approved teachers
            Route::post('/approved_teacher', 'Tenant\ApprovedTeacherController@store');
            Route::delete('/approved_teacher/{user}', 'Tenant\ApprovedTeacherController@destroy');

            // Logged user teacher
            Route::get('/teacher', 'Tenant\LoggedUserTeacherController@show');


            //Available teacher code:
            Route::get('/teacher/available_code', 'Tenant\TeacherAvailableCodeController@show');

            // People
            Route::get('/people', '\\'. PeopleController::class .'@index');
            Route::post('/people', '\\'. PeopleController::class .'@store');
            Route::put('/people/{person}', '\\'. PeopleController::class .'@update');
            Route::get('/people/{person}', '\\'. PeopleController::class .'@show');

            // User People
            Route::post('/user/{user}/people', '\\'. UserPeopleController::class .'@store');
            Route::post('/user/{user}/person', '\\'. UserPeopleController::class .'@store');

            // Online users
            Route::get('/users/online', '\\'. OnlineUsersController::class .'@index');

            // USERS
            Route::get('/users', '\\'. UsersController::class . '@index');
            Route::post('/users', '\\'. UsersController::class . '@store');
            Route::delete('/users/{user}', '\\'. UsersController::class . '@destroy');
            //DELETE MULTIPLE USERS
            Route::post('/users/multiple', '\\'. UsersController::class . '@destroyMultiple');
            Route::get('/users/{user}', '\\'. UsersController::class . '@show');

            // USER EMAILS
            //GET USER BY EMAIL
            Route::get('/users/email/{email}', '\\' . UserEmailsController::class . '@show');
            Route::put('/users/{user}/email', '\\' . UserEmailsController::class . '@update');

            // USER MOBILE
            //GET USER BY EMAIL
            Route::get('/users/mobile/{mobile}', '\\' . UserMobileController::class . '@show');
            Route::put('/users/{user}/mobile', '\\' . UserMobileController::class . '@update');

            //GET USER BY name
            Route::get('/users/name/{name}', '\\'. UserNamesController::class . '@show');

            Route::put('/user', '\\'. LoggedUserController::class. '@update');

            // User Person
            Route::post('/user_person', '\\' . UserPersonController::class . '@store');
            Route::put('/user_person/{user}', '\\' . UserPersonController::class . '@update');
            Route::delete('/user_person/{user}', '\\' . UserPersonController::class . '@destroy');

//            Roles
            Route::get('/roles', '\\'. RolesController::class . '@index');

//            Permissions
            Route::get('/permissions', '\\'. PermissionsController::class . '@index');

            // Moodle Users
            Route::get('/moodle/users', '\\'. MoodleUsersController::class .'@index');
            Route::post('/moodle/users', '\\'. MoodleUsersController::class .'@store');
            Route::post('/moodle/users/multiple', '\\'. MoodleUsersController::class .'@destroyMultiple');
            Route::delete('/moodle/users/{moodleuser}', '\\'. MoodleUsersController::class .'@destroy');

            // Moodle Password
            Route::put('/moodle/users/{moodleuser}/password', '\\'. MoodleUsersPasswordController::class .'@update');

            //Moodle users check
            Route::post('/moodle/users/check', '\\'. MoodleUsersCheckController::class .'@store');

            //Associate Moodle user to user
            Route::post('/user/{user}/moodle','\\' . UserMoodleController::class .'@store');
            Route::put('/user/{user}/moodle','\\' . UserMoodleController::class .'@update');
            Route::delete('/user/{userid}/moodle','\\' . UserMoodleController::class .'@destroy');

            //Associate Ldap user to user
            Route::post('/user/{user}/ldap','\\' . UserLdapController::class .'@store');
            Route::put('/user/{user}/ldap','\\' . UserLdapController::class .'@update');
            Route::delete('/user/{userid}/ldap','\\' . UserLdapController::class .'@destroy');

            // Available users
            // TODO: UMMMMM
//            Route::get('/users/available', 'Tenant\UsersAvailableController@index');

            // UserRoles
            Route::post('/user/{user}/role/multiple','\\' . UserRoleController::class . '@storeMultiple');
            Route::post('/user/{user}/role/{role}', '\\' . UserRoleController::class . '@store');
            Route::delete('/user/{user}/role/{role}','\\' . UserRoleController::class . '@destroy');

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
            Route::post('/user/{user}/photo','\\' . UserPhotoController::class. '@store');
            Route::delete('/user/{user}/photo','\\' . UserPhotoController::class. '@destroy');

            // User Password
            Route::put('/user/{user}/password', '\\' . UserPasswordController::class . '@update');

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
            Route::get('/gsuite/users', '\\' . GoogleUsersController::class . '@index');
            Route::post('/gsuite/users', '\\' . GoogleUsersController::class . '@store');
            Route::post('/gsuite/users/multiple', '\\' . GoogleUsersController::class . '@destroyMultiple');
            Route::delete('/gsuite/users/{user}', '\\' . GoogleUsersController::class . '@destroy');

            //Associate Gsuite user to user
            Route::post('/user/{user}/gsuite','\\' . UserGsuiteController::class .'@store');
            Route::put('/user/{user}/gsuite','\\' . UserGsuiteController::class .'@update');
            Route::delete('/user/{userid}/gsuite','\\' . UserGsuiteController::class .'@destroy');

            Route::post('/gsuite/users/search','\\' . GoogleUsersSearchController::class . '@search');

            Route::delete('/gsuite/users/{user}', '\\' . GoogleUsersController::class . '@destroy');

            // GoogleUsersPasswordController
            Route::put('/gsuite/users/{user}/password', '\\'. GoogleUsersPasswordController::class. '@update');

            //Ldap users
            Route::get('/ldap/users', '\\' . LdapUsersController::class . '@index');
            Route::post('/ldap/users', '\\' . LdapUsersController::class . '@store');

            // Ldap Users password
            Route::put('/ldap/users/{user}/password', '\\' . LdapUsersPasswordController::class . '@update');

                //Google Suite watch users TODO
            Route::post('/gsuite/users/watch', 'Tenant\GoogleUsersWatchController@store');

            //Resend user email verification email
            Route::get('/email/resend/{user}', '\\'. VerificationController::class .'@resendUser');

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

            //Git info
            Route::get('/git/info','\\' . GitController::class . '@index');

            // Studies
            Route::get('/studies','\\' . StudiesController::class . '@index');
            Route::post('/studies','\\' . StudiesController::class . '@store');
            Route::delete('/studies/{study}','\\' . StudiesController::class . '@destroy');

            Route::put('/studies/{study}/code','\\' . StudiesCodeController::class . '@update');
            Route::put('/studies/{study}/name','\\' . StudiesNameController::class . '@update');
            Route::put('/studies/{study}/shortname','\\' . StudiesShortnameController::class . '@update');

            Route::put('/studies/{study}/subject_groups_number','\\' . StudiesSubjectGroupsNumberController::class . '@update');

            Route::put('/studies/{study}/department/{department}','\\' . StudyDepartmentController::class . '@update');
            Route::put('/studies/{study}/family/{family}','\\' . StudyFamilyController::class . '@update');

            // StudyTags
            Route::get('/studies/tags','\\' . StudyTagsController::class . '@index');
            Route::get('/studies/tags/{tag}','\\' . StudyTagsController::class . '@show');
            Route::post('/studies/tags','\\' . StudyTagsController::class . '@store');
            Route::put('/studies/tags/{tag}','\\' . StudyTagsController::class . '@update');
            Route::delete('/studies/tags/{tag}','\\' . StudyTagsController::class . '@destroy');

            //Tagged studies
            Route::post('/studies/{study}/tags/{tag}','\\' . TaggedStudiesController::class . '@store');
            Route::delete('/studies/{study}/tags/{tag}', '\\' . TaggedStudiesController::class . '@destroy');

            // Subjects
            Route::get('/subjects','\\' . SubjectsController::class . '@index');
            Route::post('/subjects','\\' . SubjectsController::class . '@store');
            Route::delete('/subjects/{subject}','\\' . SubjectsController::class . '@destroy');

            Route::put('/subjects/{subject}/code','\\' .  SubjectsCodeController::class . '@update');
            Route::put('/subjects/{subject}/name','\\' .  SubjectsNameController::class . '@update');
            Route::put('/subjects/{subject}/shortname','\\' .  SubjectsShortnameController::class . '@update');

            // subjectGroups
            Route::get('/subject_groups','\\' . SubjectGroupsController::class . '@index');
            Route::post('/subject_groups','\\' . SubjectGroupsController::class . '@store');
            Route::delete('/subject_groups/{subject_group}','\\' . SubjectGroupsController::class . '@destroy');

            Route::put('/subject_groups/{subject_group}/code','\\' .  SubjectGroupsCodeController::class . '@update');
            Route::put('/subject_groups/{subject_group}/name','\\' .  SubjectGroupsNameController::class . '@update');
            Route::put('/subject_groups/{subject_group}/shortname','\\' .  SubjectGroupsShortnameController::class . '@update');

            // subjectGroups
            Route::get('/subjectGroups','\\' . SubjectGroupsController::class . '@index');
            Route::post('/subjectGroups','\\' . SubjectGroupsController::class . '@store');
            Route::delete('/subjectGroups/{subject_group}','\\' . SubjectGroupsController::class . '@destroy');

            // SubjectGroupsTags
            Route::get('/subjectGroups/tags','\\' . SubjectGroupsTagsController::class . '@index');
            Route::get('/subjectGroups/tags/{tag}','\\' . SubjectGroupsTagsController::class . '@show');
            Route::post('/subjectGroups/tags','\\' . SubjectGroupsTagsController::class . '@store');
            Route::put('/subjectGroups/tags/{tag}','\\' . SubjectGroupsTagsController::class . '@update');
            Route::delete('/subjectGroups/tags/{tag}','\\' . SubjectGroupsTagsController::class . '@destroy');

            //Tagged subject groups
            Route::post('/subjectGroups/{subjectGroup}/tags/{tag}','\\' . TaggedSubjectGroupsController::class . '@store');
            Route::delete('/subjectGroups/{subjectGroup}/tags/{tag}', '\\' . TaggedSubjectGroupsController::class . '@destroy');

            Route::put('/subject_groups/{subjectGroup}/subjects_number','\\' . SubjectGroupSubjectsNumberController::class . '@update');

            // Positions
            Route::get('/positions','\\' . PositionsController::class . '@index');
            Route::post('/positions','\\' . PositionsController::class . '@store');
            Route::delete('/positions/{position}','\\' . PositionsController::class . '@destroy');

            Route::put('/positions/{position}/code','\\' . PositionsCodeController::class . '@update');
            Route::put('/positions/{position}/name','\\' . PositionsNameController::class . '@update');
            Route::put('/positions/{position}/shortname','\\' . PositionsShortnameController::class . '@update');

            Route::post('/positions/{position}/users/{user}','\\' . PositionUsersController::class . '@store');
            Route::delete('/positions/{position}/users/{user}','\\' . PositionUsersController::class . '@destroy');

            Route::get('/positions/{position}/email/send','\\'. PositionsSendPositionAssignedEmailController::class . '@send');

            // User Type
            Route::post('/user/type','\\' . UserTypeController::class . '@store');
            Route::put('/user/{user}/type/{userType}','\\' . UserTypeController::class . '@update');
            Route::delete('/user/{user}/type','\\' . UserTypeController::class . '@destroy');

            // Notifications
            Route::get('notifications','\\' . NotificationsController::class . '@index');
            Route::post('notifications/multiple','\\' . NotificationsController::class . '@destroyMultiple');
            Route::delete('notifications/{notification}','\\' . NotificationsController::class . '@destroy');
            Route::get('/user/notifications','\\' . UserNotificationsController::class . '@index');
            Route::get('/user/unread_notifications','\\' . UserUnreadNotificationsController::class . '@index');
            Route::delete('/user/unread_notifications/all','\\' . UserUnreadNotificationsController::class . '@destroyAll');
            Route::delete('/user/unread_notifications/{notification}','\\' . UserUnreadNotificationsController::class . '@destroy');


            // Simple notifications
            Route::post('/simple_notifications/','\\' . SimpleNotificationsController::class . '@store');

            Route::post('/identifier/check', '\\' . CheckIdentifierController::class . '@store' );
        });

        Route::group(['prefix' => 'v1'], function () {
            Route::get('/menu', 'Tenant\MenuController@index');

            Route::post('/add_teacher', 'Tenant\PendingTeachersController@store');

            Route::get('/provinces','Tenant\ProvincesController@index');
            Route::get('/localities','Tenant\LocalitiesController@index');
            Route::get('/locations','Tenant\LocalitiesController@index');

            Route::get('/identifier_types','\\'. IdentifierTypesController::class . '@index');

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

