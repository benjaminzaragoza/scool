<?php

namespace App\Listeners\Users\Password;

use App\Models\MoodleUser;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class ChangeMoodlePassword.
 *
 * @package App\Listeners
 */
class ChangeMoodlePassword implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        if ($this->isRequiredToChangeMoodlePassword($event->options)) $this->changeMoodlePassword($event->user,$event->password);
    }

    /**
     * isRequiredToChangeMoodlePassword.
     *
     * @param $options
     * @return bool
     */
    private function isRequiredToChangeMoodlePassword($options) {
        if (is_array($options)) if (array_key_exists('moodle',$options)) if ($options['moodle']) return true;
        return false;
    }

    /**
     * changeMoodlePassword.
     * @param $user
     * @param $password
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function changeMoodlePassword($user,$password) {
        if ($user->moodleUser) {
            MoodleUser::change_password($user->moodleUser->id,$password);
        }
    }
}
