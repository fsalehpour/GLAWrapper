<?php

namespace GLAWrapper;

class GoogleAPI
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
     * @return object
     */
    protected function makeBTSRequestParams($cid, $lac, $mcc, $mnc)
    {
        return (object)[
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
        $response = $this->client->post($this->apiUrl . '?key=' . $this->apiKey, [
            'body' => $parameters,
            'headers' => [
                'Content-Type' => 'application/json; charset=UTF-8'
            ],
        ]);

        $this->handleExceptions($response);

        return $response->getBody();
    }

    private function handleExceptions($response)
    {
        $statusCode = $response->getStatusCode();

        if ($statusCode == 404) {
            throw new GLANotFoundException();
        }
    }
}
