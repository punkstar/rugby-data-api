<?php

require_once __DIR__ . '/../vendor/autoload.php';

$config = [];

$app = new Silex\Application($config);
$app->mount('/fixtures', new \Punkstar\RugbyFeedService\Controller\FixtureController());
$app->mount('/table', new \Punkstar\RugbyFeedService\Controller\TableController());

$app->get('/', function () {
    $links = [];

    $leagues = [
        new \Punkstar\RugbyFeed\League\Aviva(),
        new \Punkstar\RugbyFeed\League\Pro12()
    ];

    foreach ($leagues as $league) {
        $fixtures_url = 'fixtures/' . $league->getUrlKey();
        $table_url = 'table/' . $league->getUrlKey();

        $links[] = sprintf(
            '<li><a href="%s">%s</a></li>',
            $fixtures_url,
            $fixtures_url
        );

        foreach ($league->getTeams() as $team) {
            $team_url = 'fixtures/' . $league->getUrlKey() . '/' . $team->getUrlKey();
            $links[] = sprintf(
                '<li><a href="%s">%s</a></li>',
                $team_url,
                $team_url
            );
        }

        $links[] = sprintf(
            '<li><a href="%s">%s</a></li>',
            $table_url,
            $table_url
        );
    }

    return "<h1>Available Resources</h1> " . join("\n", $links);
});

$app->run();
