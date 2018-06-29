<?php

namespace App\GoogleGSuite;

use Google_Service_Directory_Group;
use PulkitJalan\Google\Facades\Google;

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
        config_google_api();
        tune_google_client();
        $this->directory = Google::make('directory');
        $this->domain = 'iesebre.com';
    }

    public function users($maxResults = 500)
    {
//        dd(get_class($this->directory->users)); //Google_Service_Directory_Resource_Users
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
            $googleUsers = collect($r->users)->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'primaryEmail' => $user->primaryEmail,
                        'isAdmin' => $user->isAdmin,
                        'name' => $user->name,
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
}