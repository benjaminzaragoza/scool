<?php

namespace App\Events\Ldap;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class LdapUserUnAssociated
 *
 * @package App\Events\Incidents
 */
class LdapUserUnAssociated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $ldapUser;

    /**
     * MoodleUserAssociated constructor.
     * @param $user
     * @param $ldapUser
     */
    public function __construct($user,$ldapUser)
    {
        $this->user = $user;
        $this->ldapUser = $ldapUser;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
