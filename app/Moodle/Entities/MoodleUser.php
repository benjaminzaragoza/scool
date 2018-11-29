<?php

namespace App\Moodle\Entities;

use GuzzleHttp\Client;

/**
 * Class MoodleUser.
 *
 * @package App\Moodle\Entities
 */
class MoodleUser
{
    public static function all($criteria = [['key' => 'email', 'value' => '%']])
    {
        $functionname = 'core_user_get_users';
        $serverurl = config('moodle.url') . config('moodle.uri') .  '?wstoken=' . config('moodle.token') . '&wsfunction='.$functionname . '&moodlewsrestformat=json';
        $client = new Client();
        $params = [ 'criteria' => $criteria ];
        $res = $client->request('POST', $serverurl, [
            'form_params' => $params
        ]);
        $result = json_decode($res->getBody());
//        dump(count($result->users));
//        var_dump($result->users);
        return $result->users;
    }
}
