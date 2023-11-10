<?php
return [
    ['GET', '/', ['\App\Controllers\ArticleController', 'defaultNews']],
    ['GET', '/search', ['\App\Controllers\ArticleController', 'newsByTopic']],
    ['GET', '/searchCountry', ['\App\Controllers\ArticleController', 'searchNewsByCountry']],
    ['GET', '/searchTime', ['\App\Controllers\ArticleController', 'searchNewsByTime']],
];