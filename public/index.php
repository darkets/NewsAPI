<?php

declare(strict_types=1);

use App\Router\Router;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

require_once __DIR__.'/../vendor/autoload.php';

$loader = new FilesystemLoader(__DIR__ . '/../app/Views');
$twig = new Environment($loader);

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__.'/../');
$dotenv->load();


$routeInfo = Router::dispatch();

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo "404 Not Found";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        break;
    case FastRoute\Dispatcher::FOUND:
        [$className, $method] = $routeInfo[1];
        $vars = $routeInfo[2];

        $client = new \GuzzleHttp\Client();
        $apiKey = $_ENV['API_KEY'];
        $api = new \App\API($client, $apiKey);
        $articleController = (new $className($api, $twig))->{$method}($vars);
        if ($articleController) {
            $templateName = 'search.twig';
            echo $twig->render('search.twig');
        }
        break;
}
