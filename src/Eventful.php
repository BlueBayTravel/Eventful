<?php

namespace BlueBayTravel\Eventful;

use SimpleXMLElement;

class Eventful
{
    /**
     * API endpoint.
     *
     * @var string
     */
    protected $apiRoot = 'http://api.eventful.com';

    /**
     * Application key.
     *
     * @var string
     */
    protected $appKey = null;

    /**
     * Username.
     *
     * @var string
     */
    private $user = null;

    /**
     * Password.
     *
     * @var string
     */
    private $password = null;

    /**
     * User Authentication Key.
     *
     * @var string
     */
    private $userKey = null;

    /**
     * Latest request URI.
     *
     * @var string
     */
    private $requestUri = null;

    /**
     * Latest response data.
     *
     * @var string
     */
    protected $responseData = null;

    /**
     * Create a new client.
     *
     * @param string $appKey
     */
    public function __construct($appKey)
    {
        $this->appKey = $appKey;
    }

    /**
     * Login and verify the user connection.
     *
     * @param string $user
     * @param string $pass
     *
     * @return bool
     */
    public function login($user, $password)
    {
        $this->user = $user;

        $this->call('users/login', []);
        $data = $this->responseData;
        $nonce = $data['nonce'];

        $response = md5($nonce.":".md5($password));

        $args = [
            'nonce'    => $nonce,
            'response' => $response,
        ];

        $r = $this->call('users/login', $args);

        $this->userKey = (string) $r->userKey;

        return true;
    }

    /**
     * Call a method of the Eventful API.
     *
     * @param string $method
     * @param mixed  $arguments
     *
     * @return SimpleXMLElement
     */
    public function call($method, $args = [])
    {
        $method = trim($method, '/ ');

        $url = $this->apiRoot.'/rest/'.$method;
        $this->requestUri = $url;

        $postArgs = [
            'appKey'  => $this->appKey,
            'user'    => $this->user,
            'userKey' => $this->userKey,
        ];

        foreach ($args as $argKey => $argValue) {
            if (is_array($argValue)) {
                foreach ($argValue as $instance) {
                    $postArgs[$argKey] = $instance;
                }
            } else {
                $postArgs[$argKey] = $argValue;
            }
        }

        $fieldsString = '';

        foreach ($postArgs as $argKey => $argValue) {
            $fieldsString .= $key.'='.urlencode($argValue).'&';
        }

        $fieldsString = rtrim($fieldsString, '&');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->requestUri);
        curl_setopt($ch, CURLOPT_POST, count($postArgs));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return data instead of display to std out

        $cResult = curl_exec($ch);
        $this->responseData = $cResult;

        curl_close($ch);

        // Process result to XML
        $data = new SimpleXMLElement($cResult);

        if ($data->getName() === 'error') {
            $error = $data['string'].": ".$data->description;
            $code = $data['string'];

            return false;
        }

        return $data;
    }
}
