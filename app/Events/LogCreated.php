<?php

namespace App\Events;

use App\Models\Log;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class LogCreated.
 *
 * @package App\Events
 */
class LogCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $log;

    /**
     * LogCreated constructor.
     *
     * @param $log
     */
    public function __construct(Log $log)
    {
        $this->log = $log;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        $channels = collect([]);
        $channels->push(new PrivateChannel('App.Log'));
        // TODO ALSO BROADCAST ON CHANNEL BY MODULE in MODULE ASSOCIATED TO ENTRY LOG
        // 'App.Log.ModuleName' -> 'App.Log.Incidents'

        // TODO ALSO BROADCAST ON CHANNEL BY USER in user ASSOCIATED TO ENTRY LOG
        // 'App.Log.user.id' -> 'App.Log.user.1'
        return $channels;
    }
}
