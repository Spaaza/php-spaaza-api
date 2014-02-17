<?php

namespace spaaza\client;

/**
 * A PHP client for interacting with the Spaaza API.
 */
class Client
{
    protected $base;
    protected $throwExceptions = false;

    protected $myprice_app_hostname;
    protected $request_details = array();
    protected $user_cookie = null;
    protected $blindlyAcceptAllCerts = false;

    /**
     * Construct a client instance.
     * @param $base_url - the base URL to use; e.g. https://apitest0.spaaza.com/
     * @param $version - the API version this client will use. Will be used to construct the API base url.
     */
    public function __construct($base_url, $version='v1')
    {
        $this->base = $base_url  . $version . '/';
    }

    public function setThrowExceptions($flag) {
        $this->throwExceptions = $flag;
    }
    /**
     * Sets the hostname of the current myprice app, if any.
     */
    public function setMyPriceAppHostname($hostname) {
        $this->myprice_app_hostname = $hostname;
    }

    public function setRequestDetails($info) {
        $this->request_details = $info;
    }

    public function setUserCookie($cookie) {
        $this->user_cookie = $cookie;
    }

    public function setBlindlyAcceptAllCerts($flag) {
	$this->blindlyAcceptAllCerts = $flag;
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
     * Do an API PUT request
     */
    public function putRequest($path, array $params = array(), $auth = null) {
        $url = $this->base . $path;
        $ch = $this->initCurl($url, $auth);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
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
	if ($this->blindlyAcceptAllCerts) {
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	}

        if (is_array($auth)) {
            if (isset($auth['session_key']))
                $headers[] = 'Session-Key: ' . $auth['session_key'];
            if (isset($auth['user_id']))
                $headers[] = 'Session-User-Id: ' . $auth['user_id'];
            if (isset($auth['username']))
                $headers[] = 'Session-Username: ' . $auth['username'];
        }
        if (!empty($this->myprice_app_hostname))
            $headers[] = 'X-MyPrice-App-Hostname: ' . $this->myprice_app_hostname;

        if (!empty($this->request_details)) 
            $headers[] = 'X-Spaaza-Request: ' . json_encode($this->request_details);
        
        if (!empty($this->user_cookie)) 
            $headers[] = 'X-Spaaza-UserCookie: ' . $this->user_cookie;

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        return $ch;
    }

    private function execCurl($ch) {
        $body = curl_exec($ch);

	$curl_error = null;
	if(curl_exec($ch) === false)
	{
	    $curl_error = curl_error($ch);
	}
        curl_close($ch);

	if ($curl_error) {
	    throw new \Exception("curl error: " . $curl_error);
	}

        $result = json_decode($body, true);
        if ($result === NULL) {
            throw new \Exception("Invalid JSON response in API call: " . $body);
        }

        if ($this->throwExceptions) {
            if (!empty($result['errors'])) {
                throw new APIException($result['errors']);
            }

            // only return the 'result' response part
            return $result['results'];
            
        } else {
            return $result;
        }
    }
    
}
