<?php

namespace App\Providers;

use Google_Client;
use Google_Service_Drive;
use Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter;
use Illuminate\Support\ServiceProvider;

/**
 * Class GoogleDriveServiceProvider.
 *
 * @package App\Providers
 */
class GoogleDriveServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        \Storage::extend('google', function($app, $config) {

            // TODO Dinamically get file from tenant
            $credentials_file = storage_path('app/gsuite_service_accounts/scool-07eed0b50a6f.json');
            $client = new Google_Client();
            $client->setAuthConfig($credentials_file);
            $client->addScope(Google_Service_Drive::DRIVE);
            // TODO Dinamically get email from tenant
            $client->setSubject('sergitur@iesebre.com');

            $service = new \Google_Service_Drive($client);

            $options = [];
            // NOT WORKING AT LEAST FOR ME
            if(isset($config['teamDriveId'])) {
                $options['teamDriveId'] = $config['teamDriveId'];
            }
            // If no folder is specified then root folder is used
            // TODO -> Dinamically get file from tenant -> IESEBRE -> SCOOL FOLDER -> Id: 1HEXWq5AwOtdPGsblWT_4IQ0ViDRaRu4q
            $folderId = '1HEXWq5AwOtdPGsblWT_4IQ0ViDRaRu4q';
            $adapter = new GoogleDriveAdapter($service, $folderId, $options);
            return new \League\Flysystem\Filesystem($adapter);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
