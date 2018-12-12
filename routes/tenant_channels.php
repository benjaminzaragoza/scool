<?php

// ******************** IMPORTANT NOTE ***************
// DO NOT PUT NON TENANT CHANNELS HERE
// This file is executed at run time once tenant info is available see EnforcyTenancy Middleware

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('App.Log', function ($user) {
    return $user->can('logs.index');
});

Broadcast::channel('App.Log.Module.{moduleName}', function ($user,$moduleName) {
    return $user->can('logs.module.list',$moduleName);
});

Broadcast::channel('App.Log.User.{id}', function ($loggedUser,$id) {
    return $loggedUser->can('logs.user.list', $id);
});

Broadcast::channel('App.Log.Loggable.Incidents.{id}', function ($user,$id) {
    $incident= Incident::findOrFail($id);
    return $user->can('logs.loggable.list', $incident);
});
