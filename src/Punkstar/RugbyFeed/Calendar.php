<?php

namespace Punkstar\RugbyFeed;

use JMBTechnologyLimited\ICalDissect\ICalParser;
use Punkstar\RugbyFeed\Calendar\Event;

class Calendar
{
    protected $ical_file;

    public function __construct($ical_data)
    {
        $filename = tempnam("/tmp", "ICAL");

        file_put_contents($filename, $ical_data);

        $this->ical_file = $filename;
    }

    /**
     * @return Event[]
     */
    public function getEvents()
    {
        $parser = new \ICal($this->ical_file);

        $raw_events = $parser->events();
        $events = array();

        foreach ($raw_events as $raw_event) {
            $events[] = Event::buildFromArray($raw_event);
        }

        return $events;
    }
}
