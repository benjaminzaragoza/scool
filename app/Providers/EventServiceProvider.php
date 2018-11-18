<?php

namespace App\Providers;

use App\Events\GoogleInvalidUserNotificationReceived;
use App\Events\GoogleUserNotificationReceived;
use App\Events\TeacherPhotosZipUploaded;
use App\Events\TenantCreated;
use App\Listeners\Authentication\LogAttemptLoginUser;
use App\Listeners\Authentication\LogLogedOutUser;
use App\Listeners\Authentication\LogLoginUser;
use App\Listeners\Authentication\LogPasswordResetUser;
use App\Listeners\Authentication\LogRegisteredUser;
use App\Listeners\Authentication\LogVerifiedUser;
use App\Listeners\CreateTenantDatabase;
use App\Listeners\LogSuccessfulLogin;
use App\Listeners\SendGoogleInvalidUserNotificationReceivedEmail;
use App\Listeners\SendGoogleUserNotificationReceivedEmail;
use App\Listeners\SyncGoogleUsers;
use App\Listeners\UnzipTeacherPhotos;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class EventServiceProvider
 * @package App\Providers
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
//            AddRolesToRegisterUser::class -> TODO
            LogRegisteredUser::class
        ],
        Logout::class => [
            LogLogedOutUser::class
        ],
        Login::class => [
            LogSuccessfulLogin::class,
            LogLoginUser::class
        ],
        Failed::class => [
            LogAttemptLoginUser::class
        ],
        PasswordReset::class => [
            LogPasswordResetUser::class
        ],
        Verified::class => [
            LogVerifiedUser::class
        ],
        // TENANTS

        TenantCreated::class => [
            CreateTenantDatabase::class,
        ],
        TeacherPhotosZipUploaded::class => [
            UnzipTeacherPhotos::class
        ],

        // GOOGLE
        GoogleUserNotificationReceived::class => [
            SendGoogleUserNotificationReceivedEmail::class,
            SyncGoogleUsers::class
        ],
        GoogleInvalidUserNotificationReceived::class => [
            SendGoogleInvalidUserNotificationReceivedEmail::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
