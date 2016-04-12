<?php

namespace dotzero;

/**
 * Class GMapsGeocode
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
class GMapsGeocode extends GMapsGeocodeBase
{
    /**
     * Set address param for geocoding.
     *
     * @param string $address Geocoding address.
     * @return GMapsGeocode
     */
    public function setAddress($address)
    {
        $this->parameters['address'] = preg_replace('/[^a-zа-я0-9., -]+/iu', '', $address);

        return $this;
    }

    /**
     * Set region param for geocoding.
     *
     * @param string $region Geocoding region.
     * @return GMapsGeocode
     */
    public function setRegion($region)
    {
        if (!preg_match('/([a-z]+)/i', $region)) {
            throw new GMapsException('Invalid region');
        }

        $this->parameters['region'] = $region;

        return $this;
    }

    /**
     * Set components param for geocoding.
     *
     * @param array $components Geocoding components.
     * @return GMapsGeocode
     */
    public function setComponents($components)
    {
        $filters = array();
        $filtersTypes = array('route', 'locality', 'administrative_area', 'postal_code', 'country');

        foreach ($components AS $name => $value) {
            if (in_array($name, $filtersTypes)) {
                $filters[] = $name . ':' . $value;
            }
        }

        $this->parameters['components'] = implode('|', $filters);

        return $this;
    }
}
