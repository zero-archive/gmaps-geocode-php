<?php

use \dotzero\GMapsGeocode;

class GMapsGeocodeTest extends PHPUnit_Framework_TestCase
{
    private $geo = null;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        $apiKey = getenv('GOOGLE_API');
        $this->assertNotEmpty($apiKey, 'The Google URL Shortener API key must not be empty.');

        $this->geo = new GMapsGeocode($apiKey);
    }

    public function testSetAddress()
    {
        $this->geo->setAddress('Helsinki');

        $this->assertAttributeContains('Helsinki', 'parameters', $this->geo);
    }

    public function testSetRegion()
    {
        $this->geo->setRegion('US');

        $this->assertAttributeContains('US', 'parameters', $this->geo);
    }

    public function testSetComponents()
    {
        $this->geo->setComponents(array(
            'route' => 'Annegatan',
            'administrative_area' => 'Helsinki',
            'country' => 'Finland'
        ));

        $expected = 'route:Annegatan|administrative_area:Helsinki|country:Finland';

        $this->assertAttributeContains($expected, 'parameters', $this->geo);
    }

    public function testSearch()
    {
        $results = $this->geo->setAddress('Helsinki')->search();

        $this->assertNotEmpty($results);

        foreach ($results AS $result) {
            $this->assertArrayHasKey('address_components', $result);
            $this->assertArrayHasKey('formatted_address', $result);
            $this->assertArrayHasKey('geometry', $result);
            $this->assertArrayHasKey('place_id', $result);
            $this->assertArrayHasKey('types', $result);
        }
    }

    /**
     * @expectedException dotzero\GMapsException
     */
    public function testEmptySearch()
    {
        $this->geo->search();
    }
}
