<?php

require_once __DIR__ . '/../vendor/autoload.php';

$config = [
    'debug' => true
];

$app = new Silex\Application($config);
$app->mount('/fixtures', new \Punkstar\RugbyFeedService\Controller\LeagueController());

$app->get('/', function () {
    $links = [];

    $leagues = [
        new \Punkstar\RugbyFeed\League\Aviva()
    ];

    foreach ($leagues as $league) {
        $league_url = 'fixtures/' . $league->getUrlKey();
        $links[] = sprintf(
            '<li><a href="%s">%s</a></li>',
            $league_url,
            $league_url
        );

        foreach ($league->getTeams() as $team) {
            $team_url = 'fixtures/' . $league->getUrlKey() . '/' . $team->getUrlKey();
            $links[] = sprintf(
                '<li><a href="%s">%s</a></li>',
                $team_url,
                $team_url
            );
        }
    }

    return "<h1>Available Resources</h1> " . join("\n", $links);
});

$app->run();
