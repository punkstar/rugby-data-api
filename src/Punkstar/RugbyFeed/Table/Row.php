<?php

namespace Punkstar\RugbyFeed\Table;

use Punkstar\RugbyFeed\Team;

class Row
{
    public $position;
    public $team;
    public $played;
    public $won;
    public $drawn;
    public $lost;
    public $for;
    public $against;
    public $bonus_points;
    public $points;

    public function toArray()
    {
        return [
            'position' => (int) $this->position,
            'team' => [
                'name' => Team::build($this->team)->name,
            ],
            'played' => (int) $this->played,
            'won' => (int) $this->won,
            'drawn' => (int) $this->drawn,
            'lost' => (int) $this->lost,
            'for' => (int) $this->for,
            'against' => (int) $this->against,
            'bonus_points' => (int) $this->bonus_points,
            'points' => (int) $this->points
        ];
    }
}