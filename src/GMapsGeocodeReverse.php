<?php

namespace dotzero;

class GMapsGeocodeReverse extends GMapsGeocodeBase
{
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

    public function setPlaceId($place_id)
    {
        $this->parameters['place_id'] = $place_id;

        return $this;
    }
}
