<?php

namespace Punkstar\RugbyFeed\Calendar;

use ICal\EventObject;
use Punkstar\RugbyFeed\Team;

class Event
{
    const REGEX_RESULT  = '/^(.*?) (\d+) - (\d+) (.*?)$/';
    const REGEX_FIXTURE = '/^(.*?) v (.*?)$/';

    public $home_team;
    public $away_team;
    public $home_score;
    public $away_score;
    public $location;
    public $kickoff;

    public static function buildFromArray($array)
    {
        $obj = new self();

        if (isset($array['LOCATION'])) {
            $obj->location = $array['LOCATION'];
        }

        if (isset($array['DTSTART'])) {
            $obj->kickoff = strtotime($array['DTSTART']);
        }

        if (isset($array['SUMMARY'])) {
            $summary = $array['SUMMARY'];
            $summary = str_replace('BT Sport', '', $summary);
            $summary = preg_replace('![/A-Z0-9]{1,}$!', '', $summary);

            $is_fixture = preg_match(self::REGEX_FIXTURE, $summary, $fixture_match);

            if ($is_fixture) {
                list($full_match, $home_team, $away_team) = $fixture_match;

                $obj->home_team = Team::build(trim($home_team));
                $obj->away_team = Team::build(trim($away_team));
            } else {
                $is_result = preg_match(self::REGEX_RESULT, $summary, $result_match);

                if ($is_result) {
                    list($full_match, $home_team, $home_score, $away_score, $away_team) = $result_match;

                    $obj->home_team = Team::build(trim($home_team));
                    $obj->away_team = Team::build(trim($away_team));
                    $obj->home_score = trim($home_score);
                    $obj->away_score = trim($away_score);
                }
            }
        }

        return $obj;
    }

    public static function buildFromIcalEvent(EventObject $event)
    {
        $array = [
            'LOCATION' => $event->location,
            'SUMMARY'  => $event->summary,
            'DTSTART'  => $event->dtstart
        ];

        return self::buildFromArray($array);
    }

    public function getHomeTeam()
    {
        return $this->home_team;
    }

    public function getAwayTeam()
    {
        return $this->away_team;
    }

    public function getHomeScore()
    {
        return $this->home_score;
    }

    public function getAwayScore()
    {
        return $this->away_score;
    }

    public function isGameFinished()
    {
        return is_numeric($this->home_score) && is_numeric($this->away_score);
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getKickoffDateTime()
    {
        return \DateTime::createFromFormat('U', $this->kickoff);
    }
}
