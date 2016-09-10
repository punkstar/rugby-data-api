<?php

namespace Punkstar\RugbyFeed;

class Team
{
    public $name;

    private static $team_map = [
        'Bath Rugby' => [
            'bath'
        ],
        'Northampton Saints' => [
            'northampton',
            'saints'
        ],
        'Exeter Chiefs' => [
            'exeter',
            'chiefs'
        ],
        'Saracens' => [
            'saracens'
        ],
        'Leicester Tigers' => [
            'leicester'
        ],
        'Wasps' => [],
        'Sale Sharks' => [
            'sale',
            'sharks'
        ],
        'Harlequins' => [
            'quins'
        ],
        'Gloucester Rugby' => [
            'gloucester'
        ],
        'Newcastle Falcons' => [
            'newcastle',
            'falcons'
        ],
        'Bristol Rugby' => [
            'bristol'
        ],
        'Worcester Warrios' => [
            'worcester'
        ],
        'Munster Rugby' => [
            'munster'
        ],
        'Edinburgh Rugby' => [
            'edinburgh'
        ],
        'Ospreys' => [],
        'Benetton Treviso' => [
            'treviso'
        ],
        'Scarlets' => [],
        'Ulster Rugby' => [
            'ulster'
        ],
        'Connacht Rugby' => [
            'connachy'
        ],
        'Newport Gwent Dragons' => [
            'newport',
            'dragons'
        ],
        'Glasgow Warriors' => [
            'glasgow'
        ],
        'Leinster Rugby' => [
            'leinster'
        ],
        'Zebre' => [],
        'Cardiff Blues' => [
            'cardiff',
            'blues'
        ],
    ];

    /**
     * @param $name
     * @return Team
     */
    public static function build($name)
    {
        foreach (self::$team_map as $team_key => $team_aliases) {
            if (in_array($name, $team_aliases)) {
                return new self($team_key);
            }
        }

        return new self($name);
    }

    public function __construct($name)
    {
        $this->name = ucwords($name);
    }

    public function getUrlKey()
    {
        return strtolower($this->name);
    }
}
