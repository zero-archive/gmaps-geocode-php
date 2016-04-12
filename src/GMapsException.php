<?php

namespace dotzero;

/**
 * Class GMapsException
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
class GMapsException extends \Exception
{
    public function __construct($msg, $code = 100)
    {
        parent::__construct($msg, $code);
    }
}
