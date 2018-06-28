<?php

namespace App\GoogleGSuite;

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
        try {
            $r = $this->directory->groups->listGroups(array('domain' => $this->domain, 'maxResults' => $maxResults));
        } catch (\Exception $e) {
            dump('Error');
            dd($e);
            return $e;
        }
        return $r->groups;
    }
}