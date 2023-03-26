<?php

use GuzzleHttp\Client;
require_once('vendor/autoload.php');
final class api {
        private string $apiKey;
        //https://api.thecatapi.com/v1/images/search?breed_ids=beng

    /**
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }


    public function getImages($breed) {
            //$url = "https://api.thecatapi.com/v1/images/search?breed_ids=$breed";

            $url = "https://api.thecatapi.com/v1/images/search?breed_ids/' . $breed";
            $client =  new Client;

            try {
                $response = $client->request('GET', $url, [
                    'headers' => [
                        'x-api-key' => $this->apiKey
                    ],
                    'query' => [
                        'limit' => 30
                    ]
                ]);
            } catch (GuzzleException $exception) {
                echo $exception->getMessage();
            }

            return json_decode($response->getBody(), true);


        }




    }