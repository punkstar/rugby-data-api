<?php

namespace Punkstar\RugbyFeed\League;

use Punkstar\RugbyFeed\Calendar;
use Punkstar\RugbyFeed\League;
use Punkstar\RugbyFeed\Parser\BBCSportTableParser;
use Punkstar\RugbyFeed\Table;
use Punkstar\RugbyFeed\Team;

abstract class AbstractLeague implements League
{
    protected $calendar_url;
    protected $table_url;
    protected $teams;
    protected $url_key;

    /**
     * @return Team[]
     */
    public function getTeams()
    {
        $inited = array();

        foreach ($this->teams as $team) {
            $inited[] = Team::build($team);
        }

        return $inited;
    }

    public function getCalendarUrl()
    {
        return $this->calendar_url;
    }

    public function getCalendar()
    {
        return Calendar::fromUrl($this->getCalendarUrl());
    }

    public function getFixtures()
    {
        return $this->getCalendar()->getEvents();
    }

    public function getUrlKey()
    {
        return $this->url_key;
    }

    public function getTable()
    {
        $bbc_sport_parser = BBCSportTableParser::fromUrl($this->table_url);
        $table = new Table($bbc_sport_parser->getRows());

        return $table;
    }
}
