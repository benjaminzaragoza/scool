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