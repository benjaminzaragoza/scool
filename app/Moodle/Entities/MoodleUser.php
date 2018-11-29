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
        return $result->users;
    }

    public static function store($user) {
        $functionname = 'core_user_create_users';
        $serverurl = config('moodle.url') . config('moodle.uri') .  '?wstoken=' . config('moodle.token') . '&wsfunction='.$functionname . '&moodlewsrestformat=json';
        $client = new Client();
        $params = [
            'users' => [ $user ]
        ];
        $res = $client->request('POST', $serverurl, [
            'form_params' => $params
        ]);
        $result = (string) $res->getBody();
        if (str_contains($result,'"exception"')) throw new \Exception($result);
        $users = json_decode(explode('\n',$result)[1]);
        return $users[0];
    }

    public static function get($user) {
        if (filter_var($user, FILTER_VALIDATE_EMAIL)) {
            return self::all([['key' => 'email', 'value' => $user]])[0];
        }
        return self::all([['key' => 'username', 'value' => $user]])[0];
    }
}
