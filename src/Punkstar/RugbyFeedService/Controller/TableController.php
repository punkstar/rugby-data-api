<?php

namespace Punkstar\RugbyFeedService\Controller;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Serializer\JsonApiSerializer;
use Punkstar\RugbyFeed\Fixtures;
use Punkstar\RugbyFeed\League\Aviva;
use Punkstar\RugbyFeed\League\Pro12;
use Punkstar\RugbyFeedService\Transformer\EventTransformer;
use Punkstar\RugbyFeedService\Transformer\RowTransformer;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Response;

class TableController implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        // creates a new controller based on the default route
        $controllers = $app['controllers_factory'];

        $controllers->get('/{league_name_in_url}', function (Application $app, $league_name_in_url) {
            $fractal = new Manager();
            $fractal->setSerializer(new JsonApiSerializer());

            if ($league_name_in_url !== 'aviva' && $league_name_in_url !== 'pro12') {
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

            switch ($league_name_in_url) {
                case 'aviva':
                    $league = new Aviva();
                    break;
                case 'pro12':
                    $league = new Pro12();
                    break;
                default:
                    throw new \Exception("League not recognised");
            }

            $data_container = new Collection($league->getTable()->getRows(), new RowTransformer());

            $data_container->setMetaValue('rel', 'self');
            $data_container->setMetaValue('uri', sprintf('/table/%s', $league->getUrlKey()));

            return new Response(
                $fractal->createData($data_container)->toJson(),
                200,
                array(
                    "Content-type" => "application/json"
                )
            );
        });

        return $controllers;
    }
}
