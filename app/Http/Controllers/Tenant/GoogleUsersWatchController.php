<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Requests\WatchGoogleSuiteUsers;
use Carbon\Carbon;
use Google_Service_Directory_Channel;
use PulkitJalan\Google\Facades\Google;
use Webpatser\Uuid\Uuid;

/**
 * Class GoogleUsersWatchController.
 *
 * @package App\Http\Controllers
 */
class GoogleUsersWatchController extends Controller
{
    /**
     * GoogleUsersWatchController constructor.
     */
    public function __construct()
    {
        tune_google_client();
    }

    /**
     * Store.
     *
     * @param WatchGoogleSuiteUsers $request
     * @return \Exception
     */
    public function store(WatchGoogleSuiteUsers $request)
    {
        $events = ['add','delete','makeAdmin','undelete','update'];
        $events = ['add'];

        $directory = Google::make('directory');
        $adminUser = 'sergitur@iesebre.com';
        try {
            $r = $directory->users->get($adminUser);
        } catch (\Exception $e) {
            abort(500,'Error retrieving Google User info for ' . $adminUser);
            return $e;
        }

        foreach ($events as $event) {
            dump($event);
            try {
                $channel = new Google_Service_Directory_Channel();
                $uuid = '364fc2f0-59cb-11e8-be14-8150c134e845';
                dump($uuid);
                $channel->setId($uuid = Uuid::generate()->string);
                $channel->setType('web_hook');
                $address = config('app.url') . '/gsuite/notifications';
                dump($address);
                $channel->setAddress($address);
                $channel->setParams(['ttl' => 8]);
                $channel->setToken(str_random(20));
                $timeInMillis = time()*1000;
                dump($timeInMillis);
                dump('NOW: ');
                dump($timeInMillis);
                dump(Carbon::createFromTimestampMs($timeInMillis)->toDateTimeString());
                dump('Proposed expiration: ');
                dump($timeInMillis + 3600000);
                dump(Carbon::createFromTimestampMs($timeInMillis + 3600000)->toDateTimeString());
                $channel->setExpiration($timeInMillis + 3600000);
//                $channel->setParams([
//                    'ttl' => 600000000000
//                ]);

                $r = $directory->users->watch($channel,[
                    'customer' => $r->customerId, // sergitur@iesebre.com customerId obtained with get to the API
                    'event' => $event,
                    'domain' => 'iesebre.com'
                ]);
//                dump($r);
            } catch (\Exception $e) {
                dump('Error!');
                dd($e);
                return $e;
            }
        }
        dump('EXPIRATION:');
        dump($r->expiration);
        dump(Carbon::createFromTimestampMs($r->expiration)->toDateTimeString());

        dump('dassad');
        //https://developers.google.com/admin-sdk/directory/v1/guides/push
//        POST https://www.googleapis.com/admin/directory/v1/users/watch?domain=domain&event=event

    }
}
