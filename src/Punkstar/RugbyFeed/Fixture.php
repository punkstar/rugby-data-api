<?php

namespace Punkstar\RugbyFeed;

class Fixture
{

    const REGEX_RESULT = '/^(.*?) (\d+) - (\d+) (.*?)$/';
    const REGEX_FIXTURE = '/^(.*?) v (.*?)$/';

    /**
     * @var Team
     */
    public $home_team;

    /**
     * @var Team
     */
    public $away_team;

    public $home_score;
    public $away_score;
    public $location;
    public $kickoff;

    /**
     * @param array $array
     * @param League $league
     *
     * @return Fixture
     * @throws \Exception
     */
    public static function buildFromArray($array, League $league)
    {
        $location = null;
        if (isset($array['LOCATION'])) {
            $location = trim($array['LOCATION']);
        }

        $kickoff = null;
        if (isset($array['DTSTART'])) {
            $kickoff = strtotime($array['DTSTART']);
        }

        $home_team = null;
        $away_team = null;
        $home_score = null;
        $away_score = null;
        if (isset($array['SUMMARY'])) {
            $summary = $array['SUMMARY'];
            $summary = str_replace('BBC', '', $summary);
            $summary = str_replace('BT Sport', '', $summary);
            $summary = str_replace('Sky', '', $summary);
            $summary = preg_replace('![/A-Z0-9]{1,}$!', '', $summary);
            $summary = str_replace('TBC: ', '', $summary);

            $is_fixture = preg_match(self::REGEX_FIXTURE, $summary, $fixture_match);

            if ($is_fixture) {
                list($full_match, $home_team, $away_team) = $fixture_match;

            } else {
                $is_result = preg_match(self::REGEX_RESULT, $summary, $result_match);

                if ($is_result) {
                    list($full_match, $home_team, $home_score, $away_score, $away_team) = $result_match;
                }
            }
        }

        $home_team = trim($home_team);
        $away_team = trim($away_team);
        $home_score = $home_score ? trim($home_score) : null;
        $away_score = $away_score ? trim($away_score) : null;

        return new self($league, $home_team, $away_team, $kickoff, $home_score, $away_score, $location);
    }

    /**
     * @param \ICal\Event $event
     * @param League $league
     *
     * @return Fixture
     * @throws \Exception
     */
    public static function buildFromICalEvent(\ICal\Event $event , League $league)
    {
        $array = [
            'LOCATION' => $event->location,
            'SUMMARY'  => $event->summary,
            'DTSTART'  => $event->dtstart,
        ];

        return self::buildFromArray($array, $league);
    }

    /**
     * Fixture constructor.
     *
     * @param League           $league
     * @param string           $home_team
     * @param string           $away_team
     * @param int              $kickoff
     * @param int|null         $home_score
     * @param int|null         $away_score
     * @param string|null      $location
     *
     * @throws \Exception
     */
    public function __construct(
        League $league,
        string $home_team,
        string $away_team,
        int $kickoff,
        $home_score = null,
        $away_score = null,
        $location = null
    ) {

        $this->home_team = $league->getTeam($home_team);
        $this->away_team = $league->getTeam($away_team);
        $this->home_score = $home_score;
        $this->away_score = $away_score;
        $this->kickoff = $kickoff;

        if (is_null($this->kickoff)) {
            throw new \Exception("Found fixture with missing kickoff.");
        }

        if (is_null($this->home_team)) {
            throw new \Exception("Found fixture with invalid home team name: " . $home_team);
        }

        if (is_null($this->away_team)) {
            throw new \Exception("Found fixture with invalid away team name: " . $away_team);
        }

        if ($location) {
            $this->location = trim($location);
        } elseif ($this->home_team) {
            $this->location = $this->home_team->getStadium();
        }
    }

    /**
     * @return Team
     */
    public function getHomeTeam()
    {
        return $this->home_team;
    }

    /**
     * @return Team
     */
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
