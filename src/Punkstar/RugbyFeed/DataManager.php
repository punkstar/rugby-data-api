<?php

namespace Punkstar\RugbyFeed;

use Punkstar\RugbyFeed\FixtureProvider\ICal;
use Punkstar\RugbyFeed\TableProvider\BBCSport;
use Symfony\Component\Yaml\Yaml;

class DataManager
{
    /**
     * @var array
     */
    protected $data;
    
    public function __construct($dataFile = null)
    {
        if ($dataFile === null) {
            $dataFile = implode(DIRECTORY_SEPARATOR, [
                __DIR__, '..', '..', '..', 'etc', 'data.yml'
            ]);
            
            $dataFile = realpath($dataFile);
        }
        
        if (!file_exists($dataFile)) {
            throw new \Exception(sprintf('Data file does not exist at %s', $dataFile));
        }
    
        $dataFileContents = Yaml::parse(file_get_contents($dataFile));
        
        if (!is_array($dataFileContents)) {
            throw new \Exception(sprintf('There seems to have been a problem parsing the data file at %s', $dataFile));
        }
        
        $this->data = $dataFileContents;
    }
    
    /**
     * @return League[]
     */
    public function getLeagues()
    {
        $leagues = [];
        
        foreach ($this->data['leagues'] as $leagueData) {
            $fixtures = new FixtureSet(ICal::fromUrl($leagueData['calendar']['url']));
            $table = new Table(BBCSport::fromUrl($leagueData['table']['url'], $this));

            $teams = array_map(function ($teamKey) {
                $data = $this->data['teams'][$teamKey];
                $data['name'] = $teamKey;
                
                return new Team($data);
            }, $leagueData['teams']);
    
            $leagues[] = new League($leagueData, $teams, $fixtures, $table);
        }
        
        return $leagues;
    }
    
    /**
     * @param $searchString
     * @return null|League
     */
    public function getLeague($searchString)
    {
        foreach ($this->getLeagues() as $league) {
            if ($league->isAliasedTo($searchString)) {
                return $league;
            }
        }
        
        return null;
    }
    
    /**
     * @return Team[]
     */
    public function getTeams()
    {
        return array_map(function ($name, $data) {
            $data['name'] = $name;
            return new Team($data);
        }, array_keys($this->data['teams']), $this->data['teams']);
    }
    
    /**
     * @param $searchString
     * @return Team
     */
    public function getTeam($searchString)
    {
        foreach ($this->getTeams() as $team) {
            if ($team->isAliasedTo($searchString)) {
                return $team;
            }
        }
    
        throw new \Exception(sprintf("Can't find team %s", $searchString));
//        return new Team([]);
    }
}