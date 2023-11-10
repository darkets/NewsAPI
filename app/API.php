<?php

declare(strict_types=1);

namespace App;

use GuzzleHttp\Client;

class API
{
    private Client $client;
    private const API_URL = 'https://newsapi.org/v2';
    private const DEFAULT_IMAGE_URL = 'https://t3.ftcdn.net/jpg/04/34/72/82/360_F_434728286_OWQQvAFoXZLdGHlObozsolNeuSxhpr84.jpg';

    public function __construct()
    {
        $this->client = new Client(['verify' => 'C:/Users/ramon/OneDrive/Dators/php7.4/cacert.pem']);
    }

    public function searchNewsByTopic($q): ?array
    {
        //$q = 'bitcoin';
        $url = self::API_URL . '/everything';
        $queryParams = [
            'q' => $q,
            'apiKey' => $_ENV['API_KEY']
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
        return null;
    }

    public function searchNewsByCountry($country): ?array
    {
        $url = self::API_URL . '/top-headlines';
        $queryParams = [
            'country' => $country,
            'apiKey' => $_ENV['API_KEY'],
        ];

        $response = $this->client->get($url, ['query' => $queryParams]);

        if ($response->getStatusCode() === 200) {
            $data = json_decode($response->getBody()->getContents(), true);

            return $data;
        }
        return null;
    }

    public function searchNewsByTime(string $q, string $from, string $to): ?array
    {
        $url = self::API_URL . '/everything';
        $queryParams = [
            'q' => $q,
            'from' => $from,
            'to' => $to,
            'apiKey' => $_ENV['API_KEY'],
        ];
        $response = $this->client->get($url, ['query' => $queryParams]);

        if ($response->getStatusCode() === 200) {
            return json_decode($response->getBody()->getContents(), true);
        }
        return null;
    }

    public function fetchDefaultNews(): ?array
    {
        $q = 'bitcoin';
        $url = self::API_URL . '/everything';
        $queryParams = [
            'q' => $q,
            'apiKey' => $_ENV['API_KEY'],
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
        return null;
    }
}