<?php

namespace dotzero;

/**
 * Class GMapsGeocodeBase
 *
 * A PHP5 library implements Geocoding and Reverse geocoding through The Google Maps Geocoding API.
 *
 * @package dotzero
 * @version 0.6
 * @author dotzero <mail@dotzero.ru>
 * @link http://www.dotzero.ru/
 * @link https://github.com/dotzero/gmaps-geocode-php
 * @link https://developers.google.com/maps/documentation/geocoding/intro?hl=en
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class GMapsGeocodeBase
{
    /**
     * @var string The Google Maps Geocoding API endpoint.
     */
    const API_URL = 'https://maps.googleapis.com/maps/api/geocode/json';

    /**
     * @var array API parameters.
     */
    protected $parameters = array();

    /**
     * GMapsGeocodeBase constructor.
     *
     * @param null|string $apiKey The Google Maps Geocoding API key.
     */
    public function __construct($apiKey = null)
    {
        $this->parameters['key'] = $apiKey;
    }

    /**
     * Send geocode request and return result.
     *
     * @return array
     * @throws GMapsException
     */
    public function search()
    {
        return $this->sendRequest();
    }

    /**
     * Send cURL GET Request and return body.
     *
     * @return array
     * @throws GMapsException
     */
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

        return $result['results'];
    }
}
