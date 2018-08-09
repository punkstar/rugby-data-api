<?php

namespace Punkstar\RugbyFeed;

class FixtureSet
{
    protected $calendars;

    /**
     * FixtureProvider[] $calenders
     **/
    public function __construct(array $calendars)
    {
        $this->calendars = $calendars;
    }

    /**
     * @return Fixture[]
     */
    public function getFixtures()
    {
        $fixtures = [];
        foreach ($this->calendars as $calendar) {
            $fixtures = array_merge($fixtures, $calendar->getFixtures());
        }

        usort($fixtures, function($a, $b) {
            return $a->kickoff <=> $b->kickoff;
        });

        return $fixtures;
    }

    /**
     * @param Team $team
     *
     * @return Fixture[]
     */
    public function getEventsFromTeam(Team $team)
    {
        $events = [];

        foreach ($this->getFixtures() as $event)
        {
            if ($team->getName() == $event->getAwayTeam()->getName() || $team->getName() == $event->getHomeTeam()->getName()) {
                $events[] = $event;
            }
        }

        return $events;
    }

    /**
     * @param Team $team
     * @param \DateTime $now
     *
     * @return null|Fixture
     */
    public function getNextFixture(Team $team, \DateTime $now)
    {
        $events = $this->getEventsFromTeam($team);

        $closest_event = null;
        $closest_event_in_days = null;

        foreach ($events as $event) {
            $diff_between_now_and_event = $now->diff($event->getKickoffDateTime());
            $days_between_now_and_event = $diff_between_now_and_event->days * (($diff_between_now_and_event->invert == 1) ? -1 : 1);

            // Event was in the past
            if ($days_between_now_and_event < 0) {
                continue;
            }

            // This is our first run through
            if ($closest_event_in_days === null) {
                $closest_event_in_days = $days_between_now_and_event;
                $closest_event = $event;
                continue;
            }

            if ($days_between_now_and_event < $closest_event_in_days) {
                $closest_event_in_days = $days_between_now_and_event;
                $closest_event = $event;
                continue;
            }
        }

        return $closest_event;
    }
}
