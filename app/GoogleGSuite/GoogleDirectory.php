<?php

namespace App\GoogleGSuite;

use App\Models\GoogleWatch;
use Carbon\Carbon;
use Google_Service_Directory_Channel;
use Google_Service_Directory_Group;
use Google_Service_Directory_User;
use PulkitJalan\Google\Facades\Google;
use Webpatser\Uuid\Uuid;

/**
 * Class GoogleDirectory.
 *
 * @package App\GoogleGSuite
 */
class GoogleDirectory
{
    protected $directory;
    protected $domain;

    /**
     * GoogleDirectory constructor.
     */
    public function __construct()
    {
        // TODO delete this comments already configured in configure_tenant using middleware
//        config_google_api();
//        tune_google_client();
        $this->directory = Google::make('directory');
        $this->domain = 'iesebre.com';
    }

    /**
     * Users.
     *
     * @param int $maxResults
     * @return array
     */
    public function users($maxResults = 500)
    {
        $continue = true;
        $users = [];
        $pageToken = null;
        while ($continue) {
            $r = $this->directory->users->listUsers([
                'domain' => $this->domain,
                'maxResults' => $maxResults,
                'pageToken' => $pageToken
            ]);
            $pageToken = $r->nextPageToken;
            if(!$r->nextPageToken) $continue = false;
            dd($r->users);
            $googleUsers = collect($r->users)->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'primaryEmail' => $user->primaryEmail,
                        'isAdmin' => $user->isAdmin,
                        'familyName' => $user->name->familyName,
                        'fullName' => $user->name->fullName,
                        'givenName' => $user->name->givenName,
                        'lastLoginTime' => $user->lastLoginTime,
                        'creationTime' => $user->creationTime,
                        'suspended' => $user->suspended,
                        'suspensionReason' => $user->suspensionReason,
                        'thumbnailPhotoUrl' => $user->thumbnailPhotoUrl,
                        'orgUnitPath' => $user->orgUnitPath,
                        'organizations' => $user->organizations,
                    ];
                });
            $users = array_merge($users,$googleUsers->toArray());
        }
        return $users;
    }

    public function user($user = null)
    {
        if(is_array($user)) {
            $this->create_user($user);
            return;
        }
        return $this->get_user($user);
    }

    /**
     * get user.
     *
     * @param $user
     * @return mixed
     */
    protected function get_user($user)
    {
        $r = $this->directory->users->get($user);
        return $r;
    }

    protected function create_user($user)
    {
        $googleUser = new Google_Service_Directory_User();

        // Mandatory:
        // First name
        // Last name
        // Primary email
        // Organizational Unit (default iesebre.com)

        // TODO
        // Optional:
        // Secondary email
        // Phone Number

        $name = new  \Google_Service_Directory_UserName();
        $name->setGivenName($user['givenName']);
        $name->setFamilyName($user['familyName']);
        $googleUser->setName($name);
        $googleUser->setPrimaryEmail($user['primaryEmail']);
        $path = '/';
        if (array_key_exists('path',$user)) $path = $user['path'];
        $googleUser->setOrgUnitPath($path);
        $changePasswordAtNextLogin = false;
        if (array_key_exists('changePasswordAtNextLogin',$user)) $changePasswordAtNextLogin = true;
        $googleUser->setChangePasswordAtNextLogin($changePasswordAtNextLogin);
        $hashFunction = 'SHA-1';
        if (array_key_exists('hashFunction',$user)) $hashFunction = $user['hashFunction'];
        $googleUser->setHashFunction($hashFunction);
        $password = str_random();
        if (array_key_exists('password',$user)) $password = $user['password'];
        $googleUser->setPassword(sha1($password));
        $this->directory->users->insert($googleUser);
    }

    /**
     * Groups.
     *
     * @param int $maxResults
     * @return \Exception
     */
    public function groups($maxResults = 500)
    {
        $r = $this->directory->groups->listGroups(array('domain' => $this->domain, 'maxResults' => $maxResults));
        return $r->groups;
    }


    public function group($group = null)
    {
        if(is_array($group)) {
            $this->create_group($group);
            return;
        }
        return $this->get_group($group);
    }

    protected function create_group($group)
    {
        $googleGroup = new Google_Service_Directory_Group();
        $googleGroup->setName($group['name']);
        $googleGroup->setEmail($group['email']);
        if (array_key_exists('description',$group)) $googleGroup->setDescription($group['description']);
        $this->directory->groups->insert($googleGroup);
    }

    /**
     * get group.
     *
     * @param $group
     * @return mixed
     */
    protected function get_group($group)
    {
        $r = $this->directory->groups->get($group);
        return $r;
    }

    /**
     * Remove user.
     *
     * @param $user
     * @return mixed
     */
    public function removeUser($user)
    {
        $this->directory->users->delete($user);
    }

    /**
     * Remove group.
     *
     * @param $group
     * @return mixed
     */
    public function removeGroup($group)
    {
        $this->directory->groups->delete($group);
    }

    /**
     * Get group members.
     *
     * @param $group
     * @param int $maxResults
     * @return mixed
     */
    public function groupMembers($group, $maxResults = 500)
    {
        return $this->directory->members->listMembers($group,['maxResults' => $maxResults]);
    }

    /**
     * Watch.
     */
    public function watch($tenant_url = null)
    {
        $events = config('scool.gsuite_events_to_watch');
        $directory = Google::make('directory');
        $adminUser = config('scool.gsuite_user');

        $r = $directory->users->get($adminUser);

        if ($tenant_url == null) $tenant_url = config('app.tenant_url');

        foreach ($events as $event) {
            $channel = new Google_Service_Directory_Channel();
            $channel->setId($uuid = Uuid::generate()->string);
            $channel->setType('web_hook');
            $address = $tenant_url . '/gsuite/notifications';
            $channel->setAddress($address);
            $channel->setToken($token = str_random(20));
            // API max is 6h? it seems yes: https://stackoverflow.com/questions/40707761/google-webhooks-getting-this-error-pushinvalidttl-invalid-ttl-value-for-channe/40707786#40707786
            $channel->setParams([
                'ttl' => 99999999999999999
            ]);
            $r = $directory->users->watch($channel,[
                'customer' => $r->customerId, // sergitur@iesebre.com customerId obtained with get to the API
                'event' => $event,
                'domain' => config('scool.gsuite_domain')
            ]);
            GoogleWatch::create([
                'channel_id' => $uuid,
                'token' => $token,
                'channel_type' => $event,
                'expiration_time2' => Carbon::createFromTimestampMs($r->expiration)
            ]);
        }
    }
}