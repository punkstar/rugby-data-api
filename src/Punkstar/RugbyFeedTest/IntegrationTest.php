<?php

namespace Punkstar\RugbyFeedTest\Calendar;

use PHPUnit\Framework\TestCase;
use Punkstar\RugbyFeed\DataManager;
use Punkstar\RugbyFeedService\App;
use Symfony\Component\HttpFoundation\Request;


class IntegrationTest extends TestCase
{
    
    /**
     * @param $route
     * @dataProvider routeDataProvider
     */
    public function testAllRoutesReturnSuccessfulResponse($route)
    {
        $app = (new App())->getApp();
        
        $request = Request::create('/' . $route);
        $response = $app->handle($request);
        
        $this->assertEquals(200, $response->getStatusCode());
    }
    
    /**
     * @return array
     */
    public function routeDataProvider()
    {
        $routes = [];
    
        $data = new DataManager();
        $leagues = $data->getLeagues();
    
        foreach ($leagues as $league) {
            $fixtures_url = 'fixtures/' . $league->getUrlKey();
            $table_url = 'table/' . $league->getUrlKey();
            
            $routes[] = [$fixtures_url];
        
            foreach ($league->getTeams() as $team) {
                $team_url = 'fixtures/' . $league->getUrlKey() . '/' . $team->getUrlKey();
                
                $routes[] = [$team_url];
            }
            
    
            $routes[] = [$table_url];
        }
        
        return $routes;
    }
}