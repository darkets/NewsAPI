<?php

declare(strict_types=1);

namespace App;

use GuzzleHttp\Client;

class API
{
    private Client $client;
    private const API_URL = 'https://newsapi.org/v2';
    private string $apiKey;
    private const DEFAULT_IMAGE_URL = 'https://t3.ftcdn.net/jpg/04/34/72/82/360_F_434728286_OWQQvAFoXZLdGHlObozsolNeuSxhpr84.jpg';

    public function __construct(Client $client, string $apiKey)
    {
        $this->client = new Client(['verify' => 'C:/Users/ramon/OneDrive/Dators/php7.4/cacert.pem']);
        $this->apiKey=$apiKey;
    }
    public function searchNewsByTopic($q)
    {
        //$q = 'bitcoin';
        $url = self::API_URL . '/everything';
        $queryParams = [
            'q' => $q,
            'apiKey' => $this->apiKey,
        ];

        $response = $this->client->get($url, ['query' => $queryParams]);

        $body = $response->getBody()->getContents();

        //var_dump($body);

        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody()->getContents(), true);

            foreach ($data['articles'] as &$article) {
                if (empty($article['urlToImage'])) {
                    $article['urlToImage'] = self::DEFAULT_IMAGE_URL;
                }
            }
            return $data;
        }
        return false;
    }
    public function searchNewsByCountry( $country)
    {
        $url = self::API_URL . '/top-headlines';
        $queryParams = [
            'country' => $country,
            'apiKey' => $this->apiKey,
        ];

        $response = $this->client->get($url, ['query' => $queryParams]);

        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody()->getContents(), true);

            return $data;
        }
        return false;
    }
    public function searchNewsByTime($q, $from, $to)
    {
        $url = self::API_URL.'/everything';
        $queryParams = [
            'q'=> $q,
            'from' => $from,
            'to'=> $to,
            'apiKey' => $this->apiKey,
        ];
        $response = $this->client->get($url, ['query' => $queryParams]);

        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody()->getContents(), true);

            return $data;
        }
        return false;
    }
    public function fetchDefaultNews()
    {
        $q = 'bitcoin';
        $url = self::API_URL . '/everything';
        $queryParams = [
            'q' => $q,
            'apiKey' => $this->apiKey,
        ];

        $response = $this->client->get($url, ['query' => $queryParams]);


        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody()->getContents(), true);

            foreach ($data['articles'] as &$article) {
                if (empty($article['urlToImage'])) {
                    $article['urlToImage'] = self::DEFAULT_IMAGE_URL;
                }
            }
            return $data;
        }
        return false;
    }
}