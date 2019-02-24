<?php

namespace App\Listeners\Users\Password;

use App\Models\Incident;
use App\Models\LdapUser;
use Cache;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class ChangeLdapPassword
 * @package App\Listeners
 */
class ChangeLdapPassword
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
        if ($this->isRequiredToChangeLdapPassword($event->options)) $this->changeLdapPassword($event->user,$event->password);
    }

    /**
     * isRequiredToChangeLdapPassword.
     *
     * @param $options
     * @return bool
     */
    private function isRequiredToChangeLdapPassword($options) {
        if (is_array($options)) if (array_key_exists('ldap',$options)) if ($options['ldap']) return true;
        return false;
    }

    /**
     * changeLdapPassword
     * @param $user
     * @param $password
     */
    private function changeLdapPassword($user,$password) {
        LdapUser::changePassword($user,$password);
    }
}
