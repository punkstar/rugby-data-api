<?php

namespace Punkstar\RugbyFeed;

use ICal\ICal;
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
     * @param $url
     * @throws \Exception
     */
    public static function fromUrl($url)
    {
        $fm = new FileManager();
        return new self($fm->getFileFromUrl($url));
    }

    /**
     * @return Event[]
     */
    public function getEvents()
    {
        $parser = new ICal($this->ical_file);

        $raw_events = $parser->events();
        $events = array();

        foreach ($raw_events as $raw_event) {
            $events[] = Event::buildFromIcalEvent($raw_event);
        }

        return $events;
    }
}
