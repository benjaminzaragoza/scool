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

// IMPORTANT NOTE: TENANT BROADCAST CHANNELS ARE DEFINED AT RUNTIME AFTER TENANT MIDDLEWARES
// SEE tenant_channels.php file

// ************************ DO NOT PUT TENANT CHANNELS HERE ******************************

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
