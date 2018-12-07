<?php

namespace App\Moodle\Clients;

/**
 * Interface ClientAdapterInterface
 * @package App\Moodle\Clients
 */
interface ClientAdapterInterface
{
    /**
     * Send API request
     * @param $function
     * @param array $arguments
     * @return mixed
     */
    public function sendRequest($function, array $arguments = []);
}
