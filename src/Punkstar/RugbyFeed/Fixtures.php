<?php

namespace Punkstar\RugbyFeed;

use Punkstar\RugbyFeed\Calendar\Event;

class Fixtures
{
    protected $calendar;

    public function __construct(Calendar $calendar)
    {
        $this->calendar = $calendar;
    }

    /**
     * @param Team $team
     *
     * @return Event[]
     */
    public function getEventsFromTeam(Team $team)
    {
        $events = array();

        foreach ($this->calendar->getEvents() as $event)
        {
            if ($team->name == $event->getAwayTeam() || $team->name == $event->getHomeTeam()) {
                $events[] = $event;
            }
        }

        return $events;
    }

    /**
     * @param Team $team
     * @param \DateTime $now
     *
     * @return null|Event
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

    protected function getDateIntervalAsSeconds(\DateInterval $interval)
    {
        return (
            $interval->s +
            $interval->m * 60 +
            $interval->h * 60 * 60 +
            $interval->d * 60 * 60 * 24 +
            $interval->m * 60 * 60 * 24 * 31 +
            $interval->y = 60 * 60 * 24 * 365
        );
    }
}
