<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SALT
    |--------------------------------------------------------------------------
    |
    | Used with hashids.
    |
    */

    'salt' => env('SCOOL_SALT','Your random salt here'),

    /*
    |--------------------------------------------------------------------------
    | GOOGLE APPS/GSUITE MAIL LISTS
    |--------------------------------------------------------------------------
    |
    | Google mail lists
    |
    */

    'teachers' => env('TEACHERS_MAIL','claustre'),  // Exemple claustre@iesebre.com (Email domain added by app)
    'mentors' => env('TEACHERS_MAIL','tutors'),

    /*
    |--------------------------------------------------------------------------
    | GOOGLE APPS/GSUITE PUSH NOTIFICATIONS
    |--------------------------------------------------------------------------
    |
    | Google apps push notifications
    |
    */
    'gsuite_domain' => env('GSUITE_DOMAIN','iesebre.com'),

    'gsuite_user' => env('GSUITE_USER','sergitur@iesebre.com'),

//    'gsuite_events_to_watch' => [
//        'add'
//    ],

    'gsuite_events_to_watch' => [
        'add',
        'delete',
        'makeAdmin',
        'undelete',
        'update'
    ],

    'gsuite_notifications_send_email' => env('GSUITE_NOTIFICATIONS_SEND_EMAIL',true),
    'gsuite_notifications_email' => env('GSUITE_NOTIFICATIONS_EMAIL','stur@iesebre.com'),

];