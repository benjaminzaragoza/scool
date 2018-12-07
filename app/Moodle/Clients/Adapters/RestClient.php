<?php

namespace App\Moodle\Clients\Adapters;

use App\Moodle\Clients\BaseAdapter;
use App\Moodle\Connection;
use Assert\Assertion;
use GuzzleHttp\Client as HttpClient;

/**
 * Class RestClient
 * @package App\Moodle\Clients
 *
 * @method HttpClient getClient()
 */
class RestClient extends BaseAdapter
{
    const OPTION_FORMAT = 'moodlewsrestformat';
    const RESPONSE_FORMAT_JSON = 'json';
    const RESPONSE_FORMAT_XML = 'xml';

    /**
     * @var string
     */
    protected $responseFormat;

    /**
     * RestClient constructor.
     * @param Connection $connection
     * @param string $responseFormat
     */
    public function __construct(Connection $connection, $responseFormat = self::RESPONSE_FORMAT_JSON)
    {
        parent::__construct($connection);
        $this->setResponseFormat($responseFormat);
    }

    /**
     * Send API request
     * @param $function
     * @param array $arguments
     * @return array|bool|float|int|\SimpleXMLElement|string
     */
    public function sendRequest($function, array $arguments = [])
    {
        $configuration = [
            self::OPTION_FUNCTION => $function,
            self::OPTION_FORMAT   => $this->responseFormat,
            self::OPTION_TOKEN    => $this->getConnection()->getToken(),
        ];
        dump($this->getClient()->getConfig('base_url'));
//        dd(get_class($this->getClient()));
        $response = $this->getClient()->post($this->getClient()->getConfig('base_url'), ['form_params' => array_merge($configuration, $arguments)]);
        $this->handleException($response);

        $formattedResponse = $this->responseFormat === self::RESPONSE_FORMAT_JSON ?
            json_decode($response->getBody()) :
            (string) $response->getBody();

        //json_decode($response->getBody())

        return $formattedResponse;
    }

    /**
     * Build client instance
     * @return HttpClient
     */
    protected function buildClient()
    {
        return new HttpClient(['base_url' => $this->getEndPoint()]);
    }

    /**
     * Set response format
     * @param string $format
     */
    protected function setResponseFormat($format)
    {
        Assertion::inArray($format, [self::RESPONSE_FORMAT_JSON, self::RESPONSE_FORMAT_XML]);
        $this->responseFormat = $format;
    }
}
