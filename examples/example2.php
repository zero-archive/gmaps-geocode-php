<?php

require_once __DIR__ . '/../vendor/autoload.php';

use \dotzero\GMapsGeocodeReverse;
use \dotzero\GMapsException;

try {
    $geo = (new GMapsGeocodeReverse('AIzaSyA4gme0AceHhs_3QSknmoiC4FsS3EdK9VE'))
        ->setLatLng('40.714224', '-73.961452')
//        ->setPlaceId('ChIJd8BlQ2BZwokRAFUEcm_qrcA')
        ->search();

    print_r($result);
} catch (GMapsException $e) {
    printf('Error (%d): %s', $e->getCode(), $e->getMessage());
}
