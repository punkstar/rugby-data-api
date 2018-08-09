<?php

namespace Punkstar\RugbyFeed;

use Punkstar\RugbyFeed\FixtureProvider\BBCSport as BBCSportFixtureProvider;
use Punkstar\RugbyFeed\FixtureProvider\ICal;
use Punkstar\RugbyFeed\TableProvider\BBCSport as BBCSportTableProvider;

class League
{

    protected $teams;
    protected $data;
    protected $fixtures;
    protected $table;

    private $teamAliasCache = [];

    /**
     * @param $data
     *
     * @throws \Exception
     * @return self
     */
    public static function buildFromArray($data, $teams) {
        $teams = array_map(function ($teamKey) use ($teams) {
            $data = $teams[$teamKey];
            $data['name'] = $teamKey;

            return new Team($data);
        }, $data['teams']);

        $league = new self($data, $teams);

        $fixtures = [];
        foreach ($data['calendar'] as $calendar) {
            switch ($calendar['type']) {
                case 'sotic':
                    $fixtures[] = ICal::fromUrl($calendar['url'], $league);
                    break;
                case 'bbc':
                    $fixtures[] = BBCSportFixtureProvider::fromUrl($calendar['url'], $league);
                    break;
                default:
                    throw new \InvalidArgumentException('Invalid calendar type found');
            }
        }

        $league->setFixtures(new FixtureSet($fixtures));

        if (isset($data['table'])) {
            $league->setTable(new Table(BBCSportTableProvider::fromUrl($data['table']['url'], $league)));
        }

        return $league;

    }

    public function __construct($data, array $teams, FixtureSet $fixtures = null, Table $table = null)
    {
        $this->data = $data;
        $this->teams = $teams;
        $this->fixtures = $fixtures;
        $this->table = $table;
    }

    public function getName()
    {
        return $this->data['name'] ?? 'unknown';
    }

    /**
     * @return string
     */
    public function getUrlKey()
    {
        return $this->data['url'] ?? 'unknown';
    }

    /**
     * @return Team[]
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * @param $teamSearchString
     *
     * @return Team
     */
    public function getTeam($teamSearchString)
    {
        if (isset($this->teamAliasCache[$teamSearchString])) {
            return $this->getTeams()[$this->teamAliasCache[$teamSearchString]];
        }

        foreach ($this->getTeams() as $k => $team) {
            if ($team->isAliasedTo($teamSearchString)) {
                $this->teamAliasCache[$teamSearchString] = $k;
                return $team;
            }
        }

        return null;
    }

    /**
     * @param $searchString
     *
     * @return bool
     */
    public function isAliasedTo($searchString)
    {
        $aliases = array_map('mb_strtolower', [$this->getName(), $this->getUrlKey()]);

        return in_array($searchString, $aliases);
    }

    /**
     * @return Fixture[]
     */
    public function getFixtures()
    {
        return $this->fixtures->getFixtures();
    }

    /**
     * @param FixtureSet $fixtures
     */
    public function setFixtures(FixtureSet $fixtures)
    {
        $this->fixtures = $fixtures;
    }

    /**
     * @param Team $team
     *
     * @return Fixture[]
     */
    public function getFixturesForTeam($team)
    {
        return $this->fixtures->getEventsFromTeam($team);
    }

    /**
     * @return Table
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @param Table $table
     */
    public function setTable($table)
    {
        $this->table = $table;
    }
}
