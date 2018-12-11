<?php

namespace App\LaravelWebSockets;

use BeyondCode\LaravelWebSockets\Apps\App;
use BeyondCode\LaravelWebSockets\Apps\AppProvider;
use Illuminate\Support\Collection;

/**
 * Class ConfigAppProvider.
 *
 * @package App\LaravelWebSockets\Apps
 */
class ConfigAppProvider implements AppProvider
{
    /** @var Collection */
    protected $apps;

    public function __construct()
    {
        $apps = [
            // scool.cat | scool.test MAIN APP
            [
                'id' => env('PUSHER_APP_ID'),
                'name' => env('APP_NAME'),
                'key' => env('PUSHER_APP_KEY'),
                'secret' => env('PUSHER_APP_SECRET'),
                'enable_client_messages' => true,
                'enable_statistics' => true,
            ]
        ];
        $apps = array_merge($apps,get_pusher_apps_from_tenants());
        $this->apps = collect($apps);
    }

    /**  @return array[\BeyondCode\LaravelWebSockets\AppProviders\App] */
    public function all(): array
    {
        return $this->apps
            ->map(function (array $appAttributes) {
                return $this->instanciate($appAttributes);
            })
            ->toArray();
    }

    public function findById($appId): ?App
    {
        $appAttributes = $this
            ->apps
            ->firstWhere('id', $appId);

        return $this->instanciate($appAttributes);
    }

    public function findByKey(string $appKey): ?App
    {
        $appAttributes = $this
            ->apps
            ->firstWhere('key', $appKey);

        return $this->instanciate($appAttributes);
    }

    public function findBySecret(string $appSecret): ?App
    {
        $appAttributes = $this
            ->apps
            ->firstWhere('secret', $appSecret);

        return $this->instanciate($appAttributes);
    }

    protected function instanciate(?array $appAttributes): ?App
    {
        if (! $appAttributes) {
            return null;
        }

        $app = new App(
            $appAttributes['id'],
            $appAttributes['key'],
            $appAttributes['secret']
        );

        if (isset($appAttributes['name'])) {
            $app->setName($appAttributes['name']);
        }

        if (isset($appAttributes['host'])) {
            $app->setHost($appAttributes['host']);
        }

        $app
            ->enableClientMessages($appAttributes['enable_client_messages'])
            ->enableStatistics($appAttributes['enable_statistics']);

        return $app;
    }
}
