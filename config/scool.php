<?php

return [

    /*
    |--------------------------------------------------------------------------
    | DATABASE
    |--------------------------------------------------------------------------
    |
    | Default users in tenant and main databases.
    |
    */

    // MAIN APP
    'main_database' => env('MAIN_DATABASE','sqlite'),

    /*
    |--------------------------------------------------------------------------
    | DEFAULT USERS
    |--------------------------------------------------------------------------
    |
    | Default users in tenant and main databases.
    |
    */

    // MAIN APP
    'admin_user_email' => env('ADMIN_USER_EMAIL','sergiturbadenas@gmail.com'),
    'admin_user_name' => env('ADMIN_USER_NAME','Sergi Tur Badenas'),
    'admin_user_password' => env('ADMIN_USER_PASSWORD','7c4a8d09ca3762af61e59520943dc26494f8941b'),

    // TENANT
    'admin_user_name_on_tenant' => env('ADMIN_USER_NAME_ON_TENANT','Sergi Tur Badenas'),
    'admin_user_email_on_tenant' => env('ADMIN_USER_EMAIL_ON_TENANT','sergiturbadenas@gmail.com'),
    'admin_username_password_on_tenant' => env('ADMIN_USER_PASSWORD_ON_TENANT','7c4a8d09ca3762af61e59520943dc26494f8941b'),

    'admin_user_email_on_tenant1' => env('ADMIN_USER_EMAIL_ON_TENANT1','Dídac Montero Borràs'),
    'admin_user_name_on_tenant1' => env('ADMIN_USER_NAME_ON_TENANT1','dmontero@iesebre.com'),
    'admin_username_password_on_tenant1' => env('ADMIN_USER_PASSWORD_ON_TENANT1','7c4a8d09ca3762af61e59520943dc26494f8941b'),

    'user_without_permissions_email' => env('USER_WITHOUT_PERMISSIONS_EMAIL','pepe@pringao.com'),
    'user_without_permissions_name' => env('USER_WITHOUT_PERMISSIONS_NAME','Pepe Pringao'),
    'user_without_permissions_password' => env('USER_WITHOUT_PERMISSIONS_PASSWORD','pringao'),

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


    /*
   |--------------------------------------------------------------------------
   | ROUTEROS
   |--------------------------------------------------------------------------
   |
   | ROUTEROS specific configurations
   |
   */

    'routeros_ip' => env('ROUTEROS_IP','192.168.88.1'),
    'routeros_user' => env('ROUTEROS_USER','admin'),
    'routeros_password' => env('ROUTEROS_PASSWORD',''),


    'ldap_base' => env('LDAP_BASE','dc=iesebre,dc=com'),
];
