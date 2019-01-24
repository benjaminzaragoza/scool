<?php

namespace App\Listeners\Incidents;

use App;
use App\Jobs\LogIncidentEvent;
use App\Models\MoodleUser;

/**
 * Class UpdateMoodleUserIdNumber
 * @package App\Listeners\Incidents
 */
class UpdateMoodleUserIdNumber
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
        MoodleUser::change_idnumber($event->moodleUser->id, $event->user->id);
    }
}
