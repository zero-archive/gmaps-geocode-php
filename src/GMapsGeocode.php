<?php

namespace dotzero;

class GMapsGeocode extends GMapsGeocodeBase
{
    public function setAddress($address)
    {
        $this->parameters['address'] = preg_replace('/[^a-zа-я0-9., -]+/iu', '', $address);

        return $this;
    }

    public function setRegion($region)
    {
        if (!preg_match('/([a-z]+)/i', $region)) {
            throw new GMapsException('Invalid region');
        }

        $this->parameters['region'] = $region;

        return $this;
    }

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
