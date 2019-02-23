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
 * Class LdapUserCreated
 *
 * @package App\Events\Incidents
 */
class LdapUserCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ldapUser;

    /**
     * LdapUserCreated constructor.
     * @param $ldapUser
     */
    public function __construct($ldapUser)
    {
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
