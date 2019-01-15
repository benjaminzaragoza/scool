# Que utilitzem?

- nao-pon/flysystem-google-drive: Driver flysistem per a Google Drive

```
composer require nao-pon/flysystem-google-drive
```

## Com obtenir les credencials de Google

- https://medium.com/@dennissmink/laravel-backup-database-to-your-google-drive-f4728a2b74bd

# Google Drive i Laravel Filesystem

Cal aconseguir credencials de Google per poder connectar l'aplicació a Google:

## Configuració accés amb Service Account

https://github.com/googleapis/google-api-php-client/blob/master/examples/service-account.php

Canvi a la configuració del client Google:

```php
 $credentials_file = storage_path('app/gsuite_service_accounts/scool-07eed0b50a6f.json');
 $client = new Google_Client();
 $client->setAuthConfig($credentials_file);
//            $client->setApplicationName("Client_Library_Examples");
 $client->addScope(Google_Service_Drive::DRIVE); // Google_Service_Drive::DRIVE = https://www.googleapis.com/auth/drive
 $client->setSubject('sergitur@iesebre.com');
```

El canvi és fa al fitxer: GoogleDriveServiceProvider

Cal també donar accés a aquest client a (primer logeu-vos amb l'usuari admin del vostre Google Suite for Education)

https://admin.google.com/iesebre.com/AdminHome?chromeless=1#OGX:ManageOauthClients

I afegiu al formulari el id del client (el trobareu al fitxer json del service account com a private_key_id) i poseu una llista dels scopes separada per comes:

https://www.googleapis.com/auth/admin.directory.group,https://www.googleapis.com/auth/admin.directory.user,https://www.googleapis.com/auth/drive

**ÉS IMPORTANT QUE mireu si ja teniu escopes del client posats i afegiu-los tots a la llista sinó treureu permisos**
 
## Getting your Refresh Token

## Getting your Root Folder ID

- https://github.com/ivanvermeyen/laravel-google-drive-demo/blob/master/README.md#create-your-google-drive-api-keys
