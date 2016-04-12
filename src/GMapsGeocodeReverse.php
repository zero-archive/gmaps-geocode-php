<?php

namespace dotzero;

/**
 * Class GMapsGeocodeReverse
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
class GMapsGeocodeReverse extends GMapsGeocodeBase
{
    /**
     * Set latitude and longitude params for reverse geocoding.
     *
     * @param mixed $lat Geocoding latitude.
     * @param mixed $lng Geocoding longitude.
     * @return GMapsGeocodeReverse
     */
    public function setLatLng($lat, $lng)
    {
        if (!preg_match('/([0-9.-]+).+?([0-9.-]+)/', $lat)) {
            throw new GMapsException('Invalid latitude');
        }

        if (!preg_match('/([0-9.-]+).+?([0-9.-]+)/', $lng)) {
            throw new GMapsException('Invalid longitude');
        }

        $this->parameters['latlng'] = sprintf('%s,%s', $lat, $lng);

        return $this;
    }

    /**
     * Set place id for reverse geocoding.
     *
     * @param mixed $place_id Geocoding Place Id.
     * @return GMapsGeocodeReverse
     */
    public function setPlaceId($place_id)
    {
        $this->parameters['place_id'] = $place_id;

        return $this;
    }
}
