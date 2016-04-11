<?php

require_once __DIR__ . '/../vendor/autoload.php';

use \dotzero\GMapsGeocode;
use \dotzero\GMapsException;

try {
    $result = (new GMapsGeocode(getenv('GOOGLE_API')))
        ->setAddress('Helsinki')
        ->setRegion('FI')
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
