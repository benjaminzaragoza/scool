<?php

return [

    'name' => env('IESEBRE_NAME', "Institut de l'Ebre"),
    'subdomain' => env('IESEBRE_SUBDOMAIN', 'iesebre'),
    'email_domain' => env('IESEBRE_EMAIL_DOMAIN', 'iesebre.com'),

    'database_name' => env('IESEBRE_DATABASE_NAME', 'iesebre'),
    'database_username' => env('IESEBRE_DATABASE_USERNAME', 'iesebre'),
    'database_password' => env('IESEBRE_DATABASE_PASSWORD', str_random()),
    'database_host' => env('IESEBRE_DATABASE_HOST', 'localhost'),
    'database_port' => env('IESEBRE_DATABASE_PORT', 3306),

    'gsuite_account_path' => env('IESEBRE_GSUITE_ACCOUNT_PATH', '/gsuite_service_accounts/scool-07eed0b50a6f.json'),
    'gsuite_admin_email' => env('IESEBRE_GSUITE_ADMIN_EMAIL', 'sergitur@iesebre.com'),
];
