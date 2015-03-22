<?php

require_once __DIR__ . '/../vendor/autoload.php';

$config = [
    'debug' => true
];

$app = new Silex\Application($config);
$app->mount('/fixtures', new \Punkstar\RugbyFeedService\Controller\LeagueController());

$app->run();
