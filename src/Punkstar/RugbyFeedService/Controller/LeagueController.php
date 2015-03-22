<?php

namespace Punkstar\RugbyFeedService\Controller;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Serializer\JsonApiSerializer;
use Punkstar\RugbyFeed\Fixtures;
use Punkstar\RugbyFeed\League\Aviva;
use Punkstar\RugbyFeed\Team;
use Punkstar\RugbyFeedService\Transformer\EventTransformer;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Response;

class LeagueController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->get('/{league_name_in_url}/fixtures', function (Application $app, $league_name_in_url) {
            $fractal = new Manager();
            $fractal->setSerializer(new JsonApiSerializer());

            if ($league_name_in_url !== 'aviva') {
                return new Response(
                    json_encode([
                        "errors" => [
                            [
                                "status" => "404",
                                "code"   => "LEAGUENOTFOUND",
                                "title"  => "Requested league '" . $league_name_in_url . "' was not found",
                                "detail" => "The league you attempted to load was not found at this URL. Note: League url keys are case sensitive."
                            ]
                        ]
                    ]),
                    404,
                    array(
                        "Content-type" => "application/json"
                    )
                );
            }

            $league = new Aviva();

            $data_container = new Collection($league->getFixtures(), new EventTransformer($league));

            $data_container->setMetaValue('rel', 'self');
            $data_container->setMetaValue('uri', sprintf('/%s/fixtures', $league->getUrlKey()));

            return new Response(
                $fractal->createData($data_container)->toJson(),
                200,
                array(
                    "Content-type" => "application/json"
                )
            );
        });

        $controllers->get('/{league_name_in_url}/{team_name_in_url}/fixtures', function (Application $app, $league_name_in_url, $team_name_in_url) {
            $fractal = new Manager();
            $fractal->setSerializer(new JsonApiSerializer());

            if ($league_name_in_url !== 'aviva') {
                return new Response(
                    json_encode([
                        "errors" => [
                            [
                                "status" => "404",
                                "code"   => "LEAGUENOTFOUND",
                                "title"  => "Requested league '" . $league_name_in_url . "' was not found",
                                "detail" => "The league you attempted to load was not found at this URL. Note: League url keys are case sensitive."
                            ]
                        ]
                    ]),
                    404,
                    array(
                        "Content-type" => "application/json"
                    )
                );
            }

            $league = new Aviva();
            $fixtures = new Fixtures($league->getCalendar());

            $team = null;

            foreach ($league->getTeams() as $team_in_league) {
                if ($team_in_league->getUrlKey() == $team_name_in_url) {
                    $team = $team_in_league;
                }
            }

            if ($team != null) {
                $data_container = new Collection(
                    $fixtures->getEventsFromTeam($team),
                    new EventTransformer($league)
                );

                return new Response(
                    $fractal->createData($data_container)->toJson(),
                    200,
                    array(
                        "Content-type" => "application/json"
                    )
                );
            } else {
                return new Response(
                    json_encode([
                        "errors" => [
                            [
                                "status" => "404",
                                "code"   => "TEAMNOTFOUND",
                                "title"  => "Requested team '" . $team_name_in_url . "' was not found",
                                "detail" => "The team you attempted to load was not found at this URL. Note: Team url keys are case sensitive."
                            ]
                        ]
                    ]),
                    404,
                    array(
                        "Content-type" => "application/json"
                    )
                );
            }
        });

        return $controllers;
    }
}
