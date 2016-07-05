<?php
namespace Deliv;

use GuzzleHttp;

/**
 * APIClient
 *
 * API Client class used to interface with Deliv API
 *
 * @author Joseph Jozwik <jjozwik@printsites.com>
 * @package deliv-php-sdk
 * @version 1.0
 * @copyright Copyright (c) 2016 PrintSites
 * @license https://opensource.org/licenses/MIT MIT
 *
 */
class APIClient
{
    /**
     * @var string $lastRequest
     */
    private $lastRequest;
    /**
     * @var object $lastRequestData
     */
    private $lastRequestData;
    /**
     * @var string $api_key (Required)
     */
    private $api_key;
    /**
     * @var string $apiUrl (Required) default https://api-sandbox.deliv.co/v2/
     */
    private $apiUrl;
    private static $defaults = array(
        'verify_ssl' => true
    );

    /**
     * APIClient constructor.
     *
     * @param string $api_key
     * @param string $apiUrl
     */
    public function __construct($api_key, $apiUrl = 'https://api-sandbox.deliv.co/v2/')
    {
        $this->api_key = $api_key;
        $this->apiUrl = $apiUrl;
        $this->opts = array_merge(self::$defaults);
    }

    /**
     * Makes HTTP Request against the API
     * @param string $url
     * @param array $parameters
     * @param boolean $request
     *
     * @return mixed
     */
    protected function request($url, $body, $parameters = array(), $request = 'GET')
    {
        $this->lastRequest = $url;
        $this->lastRequestData = $parameters;

        $parameters = array_merge($parameters, ['headers' => ['Api-Key' => $this->api_key]]);

        $client = new  GuzzleHttp\Client();

        $response = $client->request($request, $this->apiUrl . $url, $parameters);
        if ('299' < $response->getStatusCode()) {
            throw new \Exception('Deliv API Request Failed. Status: ' . $response->getStatusCode());
        }
        return json_decode((string)$response->getBody());
    }

    /**
     * Sends GET request to specified API endpoint
     * @param string $request
     * @param string $accessToken
     * @param array $parameters
     * @example http://strava.github.io/api/v3/athlete/#koms
     * @return \stdClass
     */
    public function get($request, $parameters = array())
    {

        return $this->request($request, '', $parameters, 'GET');
    }

    /**
     * Sends PUT request to specified API endpoint
     * @param string $request
     * @param string $accessToken
     * @param array $parameters
     * @example http://strava.github.io/api/v3/athlete/#update
     * @return \stdClass
     */
    public function put($request, $accessToken, $parameters = array())
    {
        $parameters = array_merge($parameters, array('access_token' => $accessToken));
        return $this->request($this->apiUrl . $request, $parameters, 'PUT');
    }

    /**
     * @param $request
     * @param array $parameters
     * @return \stdClass
     * @throws \Exception
     */
    public function post($request, $parameters = array())
    {
        return $this->request($request, '', $parameters, 'POST');
    }

    /**
     * Sends DELETE request to specified API endpoint
     * @param string $request
     * @param string $accessToken
     * @param array $parameters
     * @example http://strava.github.io/api/v3/activities/#delete
     * @return function
     */
    public function delete($request, $accessToken, $parameters = array())
    {
        $parameters = array_merge($parameters, array('access_token' => $accessToken));
        return $this->request($this->apiUrl . $request, $parameters, 'DELETE');
    }

}
