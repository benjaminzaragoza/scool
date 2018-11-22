<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('App.Log', function ($user) {
    return $user->can('logs.index');
});

Broadcast::channel('App.Log.Module.{module}', function ($user,$module) {
    return $module;
});

Broadcast::channel('App.Log.User.{id}', function ($user,$id) {
    return (int) $user->id === (int) $id;
});


//if ($this->log->module_type) {
//    $channels->push(new PrivateChannel('App.Log.Module.' . studly_case($this->log->module_type)));
//}
//if ($this->log->user_id) {
//    $channels->push(new PrivateChannel('App.Log.User.' . $this->log->user_id));
//}
//return $channels;
