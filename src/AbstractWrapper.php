<?php

/**
 * w3w-php-wrapper - A PHP library to use the what3words RESTful API
 *
 * @author Gary Gale <gary@what3words.com>
 * @copyright 2016, 2017 what3words Ltd
 * @link http://developer.what3words.com
 * @license MIT
 * @version 2.2.0
 * @package What3words\Geocoder
 */

namespace What3words\Geocoder;

abstract class AbstractWrapper {
    const TIMEOUT = 10;
    const ENDPOINT = 'https://api.what3words.com/v2';

    const TRANSPORT_CURL = 1;
    const TRANSPORT_FOPEN = 2;

    protected $key;
    protected $timeout = self::TIMEOUT;
    protected $transport = self::TRANSPORT_CURL;
    protected $endpoint = self::ENDPOINT;

    protected $handle = NULL;
    protected $context = NULL;

    // $options = [
    //     'key' => 'api-key',
    //     'timeout' => 10
    // ];

    public function __construct($options) {
        if (!isset($options['key']) || empty($options['key'])) {
            throw new \Exception('what3words: Missing API key');
        }

        $this->key = $options['key'];

        if (isset($options['timeout']) && !empty($options['timeout'])) {
            $this->timeout = $options['timeout'];
        }

        if (isset($options['endpoint']) && !empty($options['endpoint'])) {
            $this->endpoint = $options['endpoint'];
        }

        if (function_exists('curl_version')) {
            $this->transport = self::TRANSPORT_CURL;
            $this->handle = curl_init();
            curl_setopt_array($this->handle, [
                CURLOPT_TIMEOUT => $this->timeout,
                CURLOPT_RETURNTRANSFER => 1
            ]);
        }

        else if (ini_get('allow_url_fopen')) {
            $this->transport = self::TRANSPORT_FOPEN;
            $this->context = stream_context_create([
                'http' => [
                    'timeout' => $this->timeout
                ]
            ]);
        }

        else {
            throw new \Exception('what3words: PHP is not compiled with cURL support and allow_url_fopen is disabled; giving up');
        }
    }

    public function __destruct() {
        if ($this->transport === self::TRANSPORT_CURL && NULL !== $this->handle) {
            curl_close($this->handle);
        }
    }

    protected function getResponse($uri) {
        if ($this->transport === self::TRANSPORT_CURL) {
            return $this->getResponseWithCurl($uri);
        }

        else if ($this->transport === self::TRANSPORT_FOPEN) {
            return $this->getResponseWithFopen($uri);
        }

        else {
            throw new \Exception('what3words: PHP is not compiled with cURL support and allow_url_fopen is disabled; giving up');
        }
    }

    protected function getResponseWithCurl($uri) {
        $user_agent = $this->getUserAgent();
        $headers = $this->getHeaders();

        $options = [
            CURLOPT_URL => $uri
        ];
        if (isset($user_agent) && !empty($user_agent)) {
            $options[CURLOPT_USERAGENT] = $user_agent;
        }
        if (isset($headers) && !empty($headers)) {
            $options[CURLOPT_HTTPHEADER] = $headers;
        }
        curl_setopt_array($this->handle, $options);

        $payload = curl_exec($this->handle);
        if ($payload === false) {
            throw new \Exception(curl_error($this->handle));
        }

        return $payload;
    }

    protected function getResponseWithFopen($uri) {
        $user_agent = $this->getUserAgent();
        $headers = $this->getHeaders();

        $options = [
            'http' => []
        ];

        if (isset($user_agent) && !empty($user_agent)) {
            $options['http']['user_agent'] = $user_agent;
        }
        if (isset($headers) && !empty($headers)) {
            $options['http']['header'] = $headers;
        }

        if (!empty($options['http'])) {
            stream_context_set_option($this->context, $options);
        }

        $use_include_path = false;

        return file_get_contents($query, $use_include_path, $context);
    }

    abstract protected function getUserAgent();
    abstract protected function getHeaders();
}

?>
