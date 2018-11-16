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

//// LOG CHANNEL -> Enviar canvis (normalment només es generen entrades de Log)
Broadcast::channel('App.Log', function ($user) {
//    Log::debug('App.Log channel: ' . $user);
    Log::debug('App.Log channel!!!!!!!!!');
    return true;
});
