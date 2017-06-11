<?php

namespace GLAWrapper;

use FSalehpour\BTSLocator\BTSLocatorInterface;
use GuzzleHttp\Exception\ClientException;

class GoogleAPI implements BTSLocatorInterface
{
    protected $apiUrl;
    protected $client;
    protected $apiKey;

    /**
     * GoogleAPI constructor.
     * @param $apiUrl
     * @param $client
     * @param $apiKey
     */
    public function __construct($client, $apiUrl, $apiKey)
    {
        $this->apiUrl = $apiUrl;
        $this->client = $client;
        $this->apiKey = $apiKey;
    }


    /**
     * @param $cid
     * @param $lac
     * @param $mcc
     * @param $mnc
     * @return array
     */
    protected function makeBTSRequestParams($cid, $lac, $mcc, $mnc)
    {
        return [
            'cellTowers' => [[
                "cellId"            => $cid,
                "locationAreaCode"  => $lac,
                "mobileCountryCode" => $mcc,
                "mobileNetworkCode" => $mnc,
            ]]
        ];
    }

    public function getBTSLocation($cid, $lac, $mcc, $mnc)
    {
        $parameters = $this->makeBTSRequestParams($cid, $lac, $mcc, $mnc);
        return $this->geoLocate($parameters);
    }

    private function geoLocate($parameters)
    {
        try {
            $response = $this->client->post($this->apiUrl . '?key=' . $this->apiKey, [
                'body'    => json_encode($parameters),
                'headers' => [
                    'Content-Type' => 'application/json; charset=UTF-8'
                ],
            ]);

            $this->handleExceptions($response);

            $result = json_decode($response->getBody(), true);
            return [
                'lat'  => $result['location']['lat'],
                'long' => $result['location']['lng'],
            ];
        } catch (ClientException $e) {
            $this->handleExceptions($e->getResponse());

            throw $e;
        }
    }

    private function handleExceptions($response)
    {
        $statusCode = $response->getStatusCode();

        if ($statusCode == 404) {
            throw new GLANotFoundException();
        }
    }
}
