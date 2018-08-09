<?php

namespace Punkstar\RugbyFeed\FixtureProvider;

use Punkstar\RugbyFeed\FileManager;
use Punkstar\RugbyFeed\Fixture;
use Punkstar\RugbyFeed\FixtureProvider;
use Punkstar\RugbyFeed\League;

class ICal implements FixtureProvider
{
    private $ical_file;
    private $league;

    /**
     * ICal constructor.
     *
     * @param string $icalData
     * @param League $league
     *
     */
    public function __construct($icalData, $league)
    {
        $filename = tempnam("/tmp", "ICAL");
        
        file_put_contents($filename, $icalData);
        
        $this->ical_file = $filename;
        $this->league = $league;
    }

    /**
     * @param string $url
     * @param League $league
     *
     * @return ICal
     */
    public static function fromUrl($url, $league)
    {
        $fm = new FileManager();
        
        return new self($fm->getFileFromUrl($url), $league);
    }

    /**
     * @return array|Fixture[]
     * @throws \Exception
     */
    public function getFixtures()
    {
        $parser = new \ICal\ICal($this->ical_file);
        
        $raw_events = $parser->events();
        $events = array();
        
        foreach ($raw_events as $raw_event) {
            $events[] = Fixture::buildFromICalEvent($raw_event, $this->league);
        }
        
        return $events;
    }
}
