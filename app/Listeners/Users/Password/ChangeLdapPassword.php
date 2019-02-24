<?php

namespace App\Listeners\Users\Password;

use App\Models\LdapUser;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class ChangeLdapPassword
 * @package App\Listeners
 */
class ChangeLdapPassword implements ShouldQueue
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
     * @param $event
     * @throws \Adldap\Models\ModelNotFoundException
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
     * changeLdapPassword.
     *
     * @param $user
     * @param $password
     * @throws \Adldap\Models\ModelNotFoundException
     */
    private function changeLdapPassword($user,$password) {
        if ($user->ldapUser) {
            LdapUser::changePassword($user->ldapUser->uid,$password);
        }
    }
}
