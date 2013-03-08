<?php

namespace spaaza\client;

/**
 * A PHP client for interacting with the Spaaza API.
 */
class Client
{
    protected $base;

    /**
     * Construct a client instance.
     * @param $base_url - the base URL to use; e.g. https://apitest0.spaaza.com/
     * @param $version - the API version this client will use. Will be used to construct the API base url.
     */
    public function __construct($base_url, $version='v1')
    {
        $this->base = $base_url  . $version . '/';
    }

    /** 
     * Do an API GET request
     */
    public function getRequest($path, array $params = null, $auth = null) {
        $url = $this->base . $path;
        if (is_array($params))
            $url .= '?' . http_build_query($params);
        $ch = $this->initCurl($url, $auth);
        return $this->execCurl($ch);
    }

    /** 
     * Do an API POST request
     */
    public function postRequest($path, array $params = array(), $auth = null) {
        $url = $this->base . $path;
        $ch = $this->initCurl($url, $auth);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        return $this->execCurl($ch);
    }

    /** 
     * Do an API DELETE request
     */
    public function deleteRequest($path, array $params = array(), $auth = null) {
        $url = $this->base . $path;
        $ch = $this->initCurl($url, $auth);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        return $this->execCurl($ch);
    }

    /** 
     * Do an API JSON POST request
     */
    public function postJSONRequest($path, array $jsondata = array(), $auth = null) {
        $url = $this->base . $path;
        $ch = $this->initCurl($url, $auth, array('Content-type: application/json')); 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($jsondata));
        return $this->execCurl($ch);
    }


    /*
     * used to initialize a new connection resource.
     */
    private function initCurl($url, $auth = null, $extra_headers = array())
    {
        $ch = curl_init();
        $headers = array_merge($extra_headers, array('Cache-Control: private', 'Connection: Keep-Alive'));
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if (is_array($auth)) {
            if (isset($auth['session_key']))
                $headers[] = 'Session-Key: ' . $auth['session_key'];
            if (isset($auth['user_id']))
                $headers[] = 'Session-User-Id: ' . $auth['user_id'];
            if (isset($auth['username']))
                $headers[] = 'Session-Username: ' . $auth['username'];
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        return $ch;
    }

    private function execCurl($ch) {
        $body = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($body, true);
        if ($result === NULL) {
            throw new \Exception("Invalid JSON response in API call: " . $body);
        }
        return $result;
    }
    
}
