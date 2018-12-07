<?php

return [

    'admin_user_email' => env('ADMIN_USER_EMAIL','sergiturbadenas@gmail.com'),
    'admin_user_name' => env('ADMIN_USER_NAME','Sergi Tur Badenas'),
    'admin_user_password' => env('ADMIN_USER_PASSWORD','123456'),

    'admin_user_email1' => env('ADMIN_USER_EMAIL','dmontero@iesebre.com'),
    'admin_user_name1' => env('ADMIN_USER_NAME','Dídac Montero Borràs'),
    'admin_user_password1' => env('ADMIN_USER_PASSWORD','123456'),

    'admin_user_name_on_tenant' => env('ADMIN_USER_NAME_ON_TENANT','Sergi Tur Badenas'),
    'admin_user_email_on_tenant' => env('ADMIN_USER_EMAIL_ON_TENANT','sergiturbadenas@gmail.com'),
    'admin_username_password_on_tenant' => env('ADMIN_USER_PASSWORD_ON_TENANT','123456'),

    'user_without_permissions_email' => env('USER_WITHOUT_PERMISSIONS_EMAIL','pepe@pringao.com'),
    'user_without_permissions_name' => env('USER_WITHOUT_PERMISSIONS_NAME','Pepe Pringao'),
    'user_without_permissions_password' => env('USER_WITHOUT_PERMISSIONS_PASSWORD','7c4a8d09ca3762af61e59520943dc26494f8941b'),

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
