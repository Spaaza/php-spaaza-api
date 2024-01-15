<?php

namespace spaaza\client;

use GuzzleHttp\Exception\ClientException;
use Psr\Http\Message\ResponseInterface;

/**
 * A PHP client for interacting with the Spaaza API.
 */
class Client
{
    protected $throwExceptions = false;

    protected $myprice_app_hostname;
    protected $user_agent;
    protected $request_details = array();
    protected $user_cookie = null;
    protected $on_behalf_of = null;
    protected $locale = null;
    protected $api_version = null;
    protected $x_forwarded_for;

    protected $guzzle_client;

    /**
     * Construct a client instance.
     *
     * @param $base_url - the base URL to use: e.g. https://apitest0.spaaza.com/
     * @param bool $verify_certs whether to verify server SSL certificates
     */
    public function __construct($base_url, $verify_certs = true)
    {
        $this->guzzle_client = new \GuzzleHttp\Client(
            [
                'base_uri'  => $base_url,
                'verify'    => $verify_certs
            ]
        );
    }

    public function setThrowExceptions($flag) {
        $this->throwExceptions = $flag;
    }

    public function setMyPriceAppHostname($hostname) {
        $this->myprice_app_hostname = $hostname;
    }

    public function unsetMyPriceAppHostname() {
        unset($this->myprice_app_hostname);
    }

    public function setRequestDetails($info) {
        $this->request_details = $info;
    }

    public function setUserCookie($cookie) {
        $this->user_cookie = $cookie;
    }

    public function setOnBehalfOf($username) {
        $this->on_behalf_of = $username;
    }

    public function unsetOnBehalfOf() {
        unset($this->on_behalf_of);
    }

    public function setLocale($locale) {
        $this->locale = $locale;
    }

    public function setApiVersion($api_version) {
        $this->api_version = $api_version;
    }

    public function unsetApiVersion() {
        unset($this->api_version);
    }

    public function setUserAgent($user_agent) {
        $this->user_agent = $user_agent;
    }

    public function unsetUserAgent() {
        unset($this->user_agent);
    }

    public function setXForwardedFor($x_forwarded_for) {
        $this->x_forwarded_for = $x_forwarded_for;
    }

    public function unsetXForwardedFor() {
        unset($this->x_forwarded_for);
    }

    /**
     * Do an API GET request.
     *
     * @param $path
     * @param array $params
     * @param array $auth
     * @return array
     * @throws APIException
     */
    public function getRequest($path, array $params = null, $auth = null, $extra_headers = array())
    {
        return $this->makeRequest('GET', $path,
            [
                'headers' => $this->headersForRequest($auth, $extra_headers),
                'query' => $params
            ]
        );
    }

    /**
     * Do an API POST request.
     *
     * @param $path
     * @param array $params
     * @param array $auth
     * @return array
     * @throws APIException
     */
    public function postRequest($path, array $params = array(), $auth = null, $extra_headers = array()) {
        return $this->makeRequest('POST', $path,
            [
                'headers' => $this->headersForRequest($auth, $extra_headers),
                'form_params' => $params
            ]
        );
    }

    /**
     * Do an API JSON POST request.
     *
     * @param $path
     * @param array $jsondata
     * @param array $auth
     * @return array
     * @throws APIException
     */
    public function postJSONRequest($path, array $jsondata = array(), $auth = null, $extra_headers = array()) {
        return $this->makeRequest('POST', $path,
            [
                'headers' => $this->headersForRequest($auth, $extra_headers),
                'json' => $jsondata
            ]
        );
    }

    /**
     * Do an API multipart POST request.
     *
     * @param $path
     * @param array $params - expects an array of associative arrays, e.g.
     *     [
     *       [
     *         'name'     => 'imagefile',
     *         'contents' => fopen($this->test_image_path, 'r')],
     *       [
     *         'name'     => 'image_seq_num',
     *         'contents' => 1
     *       ],
     *       ...
     * @param array $auth
     * @return array
     * @throws APIException
     */
    public function postMultipartRequest($path, array $params = array(), $auth = null, $extra_headers = array()) {
        return $this->makeRequest('POST', $path,
            [
                'headers' => $this->headersForRequest($auth, $extra_headers),
                'multipart' => $params
            ]
        );
    }

    /**
     * Do an API DELETE request.
     *
     * @param $path
     * @param array $params
     * @param array $auth
     * @return array
     * @throws APIException
     */
    public function deleteRequest($path, array $params = array(), $auth = null, $extra_headers = array()) {
        return $this->makeRequest('DELETE', $path,
            [
                'headers' => $this->headersForRequest($auth, $extra_headers),
                'form_params' => $params
            ]
        );
    }

    /**
     * Do an API PUT request.
     *
     * @param $path
     * @param array $params
     * @param null $auth
     * @param array $extra_headers
     * @return array
     * @throws APIException
     */
    public function putRequest($path, array $params = array(), $auth = null, array $extra_headers = array()) {
        return $this->makeRequest('PUT', $path,
            [
                'headers' => $this->headersForRequest($auth, $extra_headers),
                'form_params' => $params
            ]
        );
    }

    /**
     * Assemble the request headers.
     *
     * @param array $auth
     * @param array $extra_headers
     * @return array
     */
    protected function headersForRequest($auth = null, $extra_headers = array()) {
        $headers = array_merge($extra_headers, [
                'Cache-Control' => 'private',
                'Connection' => 'Keep-Alive'
            ]
        );
        if (is_array($auth)) {
            if (isset($auth['session_key']))
                $headers['X-Spaaza-Session-Key'] = $auth['session_key'];
            if (isset($auth['user_id']))
                $headers['X-Spaaza-Session-User-Id'] = (string)$auth['user_id'];
            if (isset($auth['username']))
                $headers['X-Spaaza-Session-Username'] = $auth['username'];
            if (isset($auth['chain_id']))
                $headers['X-Spaaza-Session-Chain-Id'] = $auth['chain_id'];
        } elseif (is_string($auth)) {
            $headers['Authorization'] = 'Bearer ' . $auth;
        }

        if (!empty($this->user_agent))
            $headers['User-Agent'] = $this->user_agent;

        if (!empty($this->myprice_app_hostname))
            $headers['X-Spaaza-MyPrice-App-Hostname'] = $this->myprice_app_hostname;

        if (!empty($this->request_details))
            $headers['X-Spaaza-Request'] = json_encode($this->request_details);

        if (!empty($this->user_cookie))
            $headers['X-Spaaza-UserCookie'] = $this->user_cookie;

        if (!empty($this->on_behalf_of))
            $headers['X-Spaaza-On-Behalf-Of'] = $this->on_behalf_of;

        if (!empty($this->locale))
            $headers['Accept-Language'] = $this->locale;

        if (!empty($this->api_version))
            $headers['X-Spaaza-API-Version'] = $this->api_version;

        if (!empty($this->x_forwarded_for))
            $headers['X-Forwarded-For'] = $this->x_forwarded_for;

        return $headers;
    }

    /**
     * Decode the response and handle errors.
     *
     * @param ResponseInterface $res
     * @return array
     * @throws APIException
     */
    protected function handleResponse($res)
    {
        $body = (string)$res->getBody();
        $result = json_decode($body, true);
        if ($this->throwExceptions) {

            if (!empty($result['error'])) {
                // Handle errors the post-1.4.0 way
                $error[] = $result['error'];
                throw new APIException($error);
            } else if (!empty($result['errors'])) {
                throw new APIException($result['errors']);
            }

            return $result['results'];

        } else {
            return $result;
        }
    }

    /**
     * @param string $method
     * @param string $path
     * @param array $params
     * @return array
     * @throws APIException
     */
    protected function makeRequest($method, $path, array $params)
    {
        $res = null;
        try {
            $res = $this->guzzle_client->request($method, $path, $params);
        } catch (ClientException $ce) {
            $res = $ce->getResponse();
        }

        return $this->handleResponse($res);
    }

}
