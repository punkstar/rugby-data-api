<?php

namespace Punkstar\RugbyFeed\League;

use Punkstar\RugbyFeed\League;
use Punkstar\RugbyFeed\Team;

abstract class AbstractLeague implements League
{
    protected $teams;

    public function getTeams()
    {
        $inited = array();

        foreach ($this->teams as $team) {
            $inited[] = new Team($team);
        }

        return $inited;
    }
}
