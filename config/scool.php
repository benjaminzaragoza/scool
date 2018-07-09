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
    'gsuite_notifications_send_email' => env('GSUITE_NOTIFICATIONS_SEND_EMAIL',true),
    'gsuite_notifications_email' => env('GSUITE_NOTIFICATIONS_EMAIL','stur@iesebre.com'),

];