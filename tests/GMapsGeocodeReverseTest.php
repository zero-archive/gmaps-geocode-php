<?php

use \dotzero\GMapsGeocodeReverse;

class GMapsGeocodeReverseTest extends PHPUnit_Framework_TestCase
{
    private $geo = null;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        $apiKey = getenv('GOOGLE_API');
        $this->assertNotEmpty($apiKey, 'The Google URL Shortener API key must not be empty.');

        $this->geo = new GMapsGeocodeReverse($apiKey);
    }

    public function testSetLatLng()
    {
        $this->geo->setLatLng('40.714224', '-73.961452');

        $this->assertAttributeContains('40.714224,-73.961452', 'parameters', $this->geo);
    }

    /**
     * @expectedException dotzero\GMapsException
     */
    public function testSetLatLngIncorrect()
    {
        $this->geo->setLatLng('foo', 'bar');
    }

    public function testSetPlaceId()
    {
        $this->geo->setPlaceId('ChIJd8BlQ2BZwokRAFUEcm_qrcA');

        $this->assertAttributeContains('ChIJd8BlQ2BZwokRAFUEcm_qrcA', 'parameters', $this->geo);
    }

    public function testSearch()
    {
        $results = $this->geo->setLatLng('40.714224', '-73.961452')->search();

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
