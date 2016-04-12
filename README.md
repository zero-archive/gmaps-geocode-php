# PHP Google Maps Geocode

[![Build Status](https://travis-ci.org/dotzero/gmaps-geocode-php.svg?branch=master)](https://travis-ci.org/dotzero/gmaps-geocode-php)
[![Latest Stable Version](https://poser.pugx.org/dotzero/gmaps-geocode/version)](https://packagist.org/packages/dotzero/gmaps-geocode)
[![License](https://poser.pugx.org/dotzero/gmaps-geocode/license)](https://packagist.org/packages/dotzero/gmaps-geocode)

A PHP5 library implements Geocoding and Reverse geocoding through The Google Maps Geocoding API.

Geocoding is the process of converting addresses (like "1600 Amphitheatre Parkway, Mountain View, CA") into geographic coordinates (like latitude 37.423021 and longitude -122.083739). Reverse geocoding is the process of converting geographic coordinates into a human-readable address.

## Usage

To use the Google Maps Geocoding API, you need an API key. To acquire an API key follow [the instructions](https://developers.google.com/maps/documentation/geocoding/get-api-key).

### Geocoding (Latitude/Longitude Lookup)

```php
try {
    $result = (new GMapsGeocode('YOUR_GOOGLE_API'))
        ->setAddress('Helsinki')
//        ->setRegion('FI')
        ->setComponents(array(
            'route' => 'Annegatan',
            'administrative_area' => 'Helsinki',
            'country' => 'Finland'
        ))
        ->search();

    print_r($result);
} catch (GMapsException $e) {
    printf('Error (%d): %s', $e->getCode(), $e->getMessage());
}
```

Required method are `setAddress` or `setComponents` in a geocoding request and `setRegion` is optional.

[Official documentation](https://developers.google.com/maps/documentation/geocoding/intro?hl=en#ComponentFiltering) contains more about Component Filtering.

### Reverse Geocoding (Address Lookup)

```php
try {
    $geo = (new GMapsGeocodeReverse('YOUR_GOOGLE_API'))
        ->setLatLng('40.714224', '-73.961452')
//        ->setPlaceId('ChIJd8BlQ2BZwokRAFUEcm_qrcA')
        ->search();

    print_r($result);
} catch (GMapsException $e) {
    printf('Error (%d): %s', $e->getCode(), $e->getMessage());
}
```

Required method are `setLatLng` or `setPlaceId` in a reverse geocoding request.

## Install

### Via composer:

```bash
$ composer require dotzero/gmaps-geocode
```

### Without composer

Clone the project using:

```bash
$ git clone https://github.com/dotzero/gmaps-geocode-php/
```

and include the source files with:

```php
    require_once("gmaps-geocode-php/src/GMapsException.php");
    require_once("gmaps-geocode-php/src/GMapsGeocodeBase.php");
    require_once("gmaps-geocode-php/src/GMapsGeocode.php");
    require_once("gmaps-geocode-php/src/GMapsGeocodeReverse.php");
```

## Test

First install the dependencies, and after you can run:

```bash
GOOGLE_API=YOUR_GOOGLE_API vendor/bin/phpunit
```

## License

Licensed under the MIT license: http://www.opensource.org/licenses/mit-license.php
