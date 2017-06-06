<?php
/**
 * Created by PhpStorm.
 * User: faramarz
 * Date: 5/31/17
 * Time: 15:28
 */

use GLAWrapper\GoogleAPI;
use PHPUnit\Framework\TestCase;

class GoogleAPITest extends TestCase
{
    protected $api;
    protected $client;
    protected $response;

    protected function setUp()
    {
        parent::setUp();
        $this->client = $this->getMockBuilder('Client')
            ->setMethods(['post'])
            ->getMock();
        $this->response = $this->createMock(GuzzleHttp\Psr7\Response::class);
        $this->api = new GoogleAPI($this->client, 'dkkdkd', 'dkkkdk');
    }

    /** @test */
    public function it_returns_geo_data_of_a_bts_antenna()
    {
        $expected='{ "location": { "lat": 36.8456427, "lng": 54.4393363 }, "accuracy": 79477.0 }';
        $this->response->method('getBody')
            ->willReturn($expected);
        $this->client->method('post')
            ->willReturn($this->response);
        $btsGeo = $this->api->getBTSLocation(28674837, 12550, 432, 11);
        $this->assertJsonStringEqualsJsonString($expected, $btsGeo);
    }

    /** @test */
    public function it_throws_exception_if_bts_antenna_is_not_found()
    {
        $expected = '{ "error": { "errors": [ '
            . '{ "domain": "geolocation", "reason": "notFound", "message": "Not Found" }'
            . ' ], "code": 404, "message": "Not Found" } }';
        $this->response->method('getStatusCode')
            ->willReturn(404);
        $this->response->method('getBody')
            ->willReturn($expected);
        $this->client->method('post')
            ->willReturn($this->response);
        $this->expectException('GLAWrapper\GLANotFoundException');
        $btsGeo = $this->api->getBTSLocation(0,0,0,0);
    }

    // todo: it returns geo location of a wifi router

    // todo: it throws exception if wifi router is not found
}
