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

];