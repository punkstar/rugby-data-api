<?php

namespace Punkstar\RugbyFeed\Test;

use Punkstar\RugbyFeed\Calendar;

class CalendarTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testCorrectNumberOfEvents()
    {
        $calendar = $this->getCalendarWithAvivaFixtureData();
        $this->assertEquals(132, count($calendar->getEvents()));
    }

    protected function getCalendarWithAvivaFixtureData()
    {
        $file = $this->getAvivaFixtureDataFileName();

        if (!file_exists($file)) {
            throw new \Exception("Could not load fixture file $file");
        }

        if (!is_readable($file)) {
            throw new \Exception("Could not read fixture file $file");
        }

        return new Calendar(file_get_contents($file));
    }

    protected function getAvivaFixtureDataFileName()
    {
        return join(DIRECTORY_SEPARATOR, array(
            __DIR__,
            '..',
            '..',
            '..',
            'test',
            'fixtures',
            'example.ics'
        ));
    }
}
