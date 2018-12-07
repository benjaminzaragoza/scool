<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Moodle token
    |--------------------------------------------------------------------------
    |
    | Model web service access token
    | Inici / ► Administració del lloc / ► Connectors / ► Serveis web / ► Gestiona tokens
    | https://www.iesebre.com/moodle/admin/settings.php?section=webservicetokens
    |
    */

    'token' => env('MOODLE_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Moodle url
    |--------------------------------------------------------------------------
    |
    | Moodle URL.
    |
    */
    'url' => 'https://www.iesebre.com/moodle',

    /*
    |--------------------------------------------------------------------------
    | Moodle rest server URI
    |--------------------------------------------------------------------------
    |
    | Moodle rest server URI
    |
    */
    'uri' => '/webservice/rest/server.php',
];
