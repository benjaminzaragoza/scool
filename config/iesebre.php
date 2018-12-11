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

    'pusher_app_id' => env('IESEBRE_PUSHER_APP_ID', 668468),
    'pusher_app_name' => env('IESEBRE_NAME', "Institut de l'Ebre"),
    'pusher_app_key' => env('IESEBRE_PUSHER_APP_KEY', '6f627646afb1261d5b50'),
    'pusher_app_secret' => env('IESEBRE_PUSHER_APP_SECRET', ''),
    'pusher_enable_client_messages' => env('IESEBRE_PUSHER_ENABLE_CLIENT_MESSAGES', true),
    'pusher_enable_statistics' => env('IESEBRE_PUSHER_ENABLE_STATISTICS', true),

];
