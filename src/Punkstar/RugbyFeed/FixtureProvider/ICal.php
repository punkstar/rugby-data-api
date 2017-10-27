<?php

namespace Punkstar\RugbyFeed\FixtureProvider;

use Punkstar\RugbyFeed\FileManager;
use Punkstar\RugbyFeed\Fixture;
use Punkstar\RugbyFeed\FixtureProvider;

class ICal implements FixtureProvider
{
    protected $ical_file;
    
    public function __construct($icalData)
    {
        $filename = tempnam("/tmp", "ICAL");
        
        file_put_contents($filename, $icalData);
        
        $this->ical_file = $filename;
    }
    
    public static function fromUrl($url)
    {
        $fm = new FileManager();
        
        return new self($fm->getFileFromUrl($url));
    }

    /**
     * @return Fixture[]
     */
    public function getFixtures()
    {
        $parser = new \ICal\ICal($this->ical_file);
        
        $raw_events = $parser->events();
        $events = array();
        
        foreach ($raw_events as $raw_event) {
            $events[] = Fixture::buildFromICalEvent($raw_event);
        }
        
        return $events;
    }
}
