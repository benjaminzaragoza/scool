<?php

namespace App\Providers;

use App\Events\GoogleUserNotificationReceived;
use App\Events\TeacherPhotosZipUploaded;
use App\Events\TenantCreated;
use App\Listeners\CreateTenantDatabase;
use App\Listeners\SendGoogleUserNotificationReceivedEmail;
use App\Listeners\UnzipTeacherPhotos;
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
        TenantCreated::class => [
            CreateTenantDatabase::class,
        ],
        TeacherPhotosZipUploaded::class => [
            UnzipTeacherPhotos::class
        ],
        GoogleUserNotificationReceived::class => [
            SendGoogleUserNotificationReceivedEmail::class
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
