<?php

namespace Punkstar\RugbyFeed;

use Punkstar\RugbyFeed\FixtureProvider\ICal;
use Punkstar\RugbyFeed\FixtureProvider\BBCSport as BBCSportFixtureProvider;
use Punkstar\RugbyFeed\TableProvider\BBCSport as BBCSportTableProvider;
use Symfony\Component\Yaml\Yaml;

class DataManager
{

    /**
     * @var League[]
     */
    protected $leagues;

    /**
     * @var array
     */
    protected $data;

    /**
     * DataManager constructor.
     *
     * @param string|null $dataFile
     *
     * @throws \Exception
     */
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
     * @throws \Exception
     */
    public function getLeagues()
    {
        $leagues = [];

        foreach ($this->data['leagues'] as $leagueData) {
            $league = League::buildFromArray($leagueData, $this->data['teams']);

            $leagues[] = $league;
        }

        return $leagues;
    }

    /**
     * @param $url
     * @return null|League
     * @throws \Exception
     */
    public function getLeague($url)
    {

        if (isset($this->leagues[$url])) {
            return $this->leagues[$url];
        }

        foreach ($this->data['leagues'] as $leagueData) {
            if ($leagueData['url'] == $url) {
                $this->leagues[$url] = League::buildFromArray($leagueData, $this->data['teams']);
                return $this->leagues[$url];
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
     * @throws \Exception
     */
    public function getTeam($searchString)
    {
        foreach ($this->getTeams() as $team) {
            if ($team->isAliasedTo($searchString)) {
                return $team;
            }
        }

        throw new \Exception(sprintf("Can't find team %s", $searchString));
    }
}
