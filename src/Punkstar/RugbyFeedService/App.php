<?php

namespace Punkstar\RugbyFeedService;

use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Serializer\JsonApiSerializer;
use Punkstar\RugbyFeed\DataManager;
use Punkstar\RugbyFeed\FixtureSet;
use Punkstar\RugbyFeed\League\Aviva;
use Punkstar\RugbyFeed\League\Pro14;
use Punkstar\RugbyFeedService\Transformer\FixtureTransformer;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Response;

class App
{
    protected $app;
    
    public function __construct()
    {
        $config = ['debug' => true];
    
        $this->app = new Application($config);
    
        $this->app->mount('/fixtures', new Controller\FixtureController());
        $this->app->mount('/table', new Controller\TableController());
    
        $this->app->get('/', function () {
            $links = [];
        
            $data = new DataManager();
            $leagues = $data->getLeagues();
        
            foreach ($leagues as $league) {
                $fixtures_url = 'fixtures/' . $league->getUrlKey();
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

                if ($league->getTable()) {
                    $table_url = 'table/' . $league->getUrlKey();
                    $links[] = sprintf(
                        '<li><a href="%s">%s</a></li>',
                        $table_url,
                        $table_url
                    );
                }
            }
        
            return "<h1>Available Resources</h1> " . implode("\n", $links);
        });
    }
    
    public function getApp()
    {
        return $this->app;
    }
}