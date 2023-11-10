<?php

declare(strict_types=1);

namespace App\Controllers;

use App\API;
use Twig\Environment;

class ArticleController
{
    private Api $api;
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->api = new API();
        $this->twig = $twig;
    }

    public function index(): void
    {
        $searchType = $_GET['searchType'] ?? '';

        if (empty($searchType)) {
            echo $this->twig->render('homepage.twig');
            return;
        }

        switch ($searchType) {
            case 'topic':
                $q = $_GET['q'] ?? '';
                $newsByTopic = $this->api->searchNewsByTopic($q);
                var_dump($newsByTopic);
                $this->renderTemplate(['news' => $newsByTopic]);
                break;

            case 'country':
                $country = $_GET['country'] ?? '';
                $newsByCountry = $this->api->searchNewsByCountry($country);
                $this->renderTemplate(['news' => $newsByCountry]);
                break;

            case 'time':
                $from = $_GET['from'] ?? '';
                $to = $_GET['to'] ?? '';
                $topic = $_GET['q'];
                $newsByTime = $this->api->searchNewsByTime($topic, $from, $to);
                $this->renderTemplate(['news' => $newsByTime]);
                break;

            default:
                echo "Invalid request";
                exit();
        }
    }

    private function renderTemplate(array $data): void
    {
        echo $this->twig->render('search.twig', $data);
    }

    public function newsByTopic($q)
    {
        $q = $_GET['q'] ?? '';
        $newsByTopic = $this->api->searchNewsByTopic($q);
        $this->renderTemplate(['news' => $newsByTopic]);
    }

    public function searchNewsByCountry(): void
    {
        $country = $_GET['country'] ?? '';

        if (empty($country)) {
            echo $this->twig->render('homepage.twig');
            return;
        }

        $newsByCountry = $this->api->searchNewsByCountry($country);
        $this->renderTemplate(['news' => $newsByCountry]);
    }

    public function searchNewsByTime(): void
    {
        $q = $_GET['q'] ?? '';
        $from = $_GET['from'] ?? '';
        $to = $_GET['to'] ?? '';

        if (!$this->isValidDate($from) || !$this->isValidDate($to)) {
            $errorMessage = 'Invalid date format or range. Dates must be within the last month.';
            $this->renderTemplate(['error' => $errorMessage]);
            return;
        }
        if (empty($from) || empty($to)) {
            echo $this->twig->render('homepage.twig');
            return;
        }

        $newsByTime = $this->api->searchNewsByTime($q, $from, $to);
        $this->renderTemplate(['news' => $newsByTime]);
    }

    public function defaultNews(): void
    {
        $defaultNews = $this->api->fetchDefaultNews();

        $this->renderTemplate(['news' => $defaultNews]);
    }

    function isValidDate($dateString): bool
    {
        $timestamp = strtotime($dateString);

        if ($timestamp !== false && $timestamp < strtotime('now') && $timestamp > strtotime('-1 month')) {
            return true;
        }
        return false;
    }
}