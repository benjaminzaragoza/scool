<?php

namespace App\Listeners\Moodle;

use App;
use App\Jobs\LogIncidentEvent;
use App\Models\MoodleUser;

/**
 * Class UnsetMoodleUserIdNumber
 * @package App\Listeners\Incidents
 */
class UnsetMoodleUserIdNumber
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  object $event
     * @return void
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle($event)
    {
        MoodleUser::change_idnumber($event->moodleUser, '');
    }
}
