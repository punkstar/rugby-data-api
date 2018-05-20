<?php

namespace Punkstar\RugbyFeed\Table;

use Punkstar\RugbyFeed\Team;

class Row
{
    /**
     * @var Team
     */
    public $team;
    
    public $position;
    public $played;
    public $won;
    public $drawn;
    public $lost;
    public $for;
    public $against;
    public $points_difference;
    public $bonus_points;
    public $points;
    public $conference;

    public function toArray()
    {
        return [
            'position' => (int) $this->position,
            'team' => $this->team->getName(),
            'played' => (int) $this->played,
            'won' => (int) $this->won,
            'drawn' => (int) $this->drawn,
            'lost' => (int) $this->lost,
            'for' => (int) $this->for,
            'against' => (int) $this->against,
            'points_difference' => (int) $this->points_difference,
            'bonus_points' => (int) $this->bonus_points,
            'points' => (int) $this->points,
            'conference' => $this->conference
        ];
    }
}