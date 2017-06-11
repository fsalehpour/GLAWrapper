<?php
/**
 * Created by PhpStorm.
 * User: faramarz
 * Date: 5/31/17
 * Time: 15:28
 */

use FSalehpour\GoogleMapsGeolocationAPI\GoogleMapsGeoLocator;
use FSalehpour\GoogleMapsGeolocationAPI\GoogleMapsGeoLocatorException;
use PHPUnit\Framework\TestCase;

class GoogleMapGeoLocatorTest extends TestCase
{
    protected $api;
    protected $client;

    protected function setUp()
    {
        parent::setUp();
        $this->client = new \GuzzleHttp\Client();
        $this->api = new GoogleMapsGeoLocator(
            $this->client,
            'https://www.googleapis.com/geolocation/v1/geolocate',
            'AIzaSyBSzs52mxNyS9z3mYKElBVp1_LWYWh-V0I'
        );
    }

    /** @test */
    public function it_returns_geo_data_of_a_bts_antenna()
    {
        $btsGeo = $this->api->getBTSLocation(28674837, 12550, 432, 11);
        $this->assertArrayHasKey('lat', $btsGeo);
        $this->assertArrayHasKey('long', $btsGeo);
        $this->assertInternalType('float', $btsGeo['lat']);
        $this->assertInternalType('float', $btsGeo['long']);
    }

    /** @test */
    public function it_throws_exception_if_bts_antenna_is_not_found()
    {
        $this->expectException(GoogleMapsGeoLocatorException::class);
        $this->api->getBTSLocation(0,0,4321,0);
    }

    // todo: it returns geo location of a wifi router

    // todo: it throws exception if wifi router is not found
}
