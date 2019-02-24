<?php

namespace App\Listeners\Users\Password;

use App\Models\Incident;
use Cache;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class ChangeMoodlePassword
 * @package App\Listeners
 */
class ChangeMoodlePassword
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

    }
}
