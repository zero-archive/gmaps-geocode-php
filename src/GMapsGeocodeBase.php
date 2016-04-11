<?php

namespace dotzero;

class GMapsGeocodeBase
{
    const API_URL = 'https://maps.googleapis.com/maps/api/geocode/json';

    protected $parameters = array();

    public function __construct($apiKey = null)
    {
        $this->parameters['key'] = $apiKey;
    }

    public function search()
    {
        return $this->sendRequest();
    }

    protected function sendRequest()
    {
        $ch = curl_init(self::API_URL . '?' . http_build_query($this->parameters));

        curl_setopt_array($ch, array(
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_RETURNTRANSFER => true,
        ));

        if (!$result = curl_exec($ch)) {
            $error = curl_error($ch);
            $errno = curl_errno($ch);
            curl_close($ch);

            throw new GMapsException($error, $errno);
        }

        curl_close($ch);

        $result = json_decode($result, true);

        if (isset($result['error_message'])) {
            throw new GMapsException($result['error_message']);
        }

        return (count($result['results']) == 1) ? array_shift($result['results']) : $result['results'];
    }
}
