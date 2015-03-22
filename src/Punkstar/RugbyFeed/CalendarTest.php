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
        $calendar = $this->getCalendarWithFixtureData();
        $this->assertEquals(132, count($calendar->getEvents()));
    }

    protected function getCalendarWithFixtureData()
    {
        $file = $this->getFixtureDataFileName();

        if (!file_exists($file)) {
            throw new \Exception("Could not load fixture file $file");
        }

        if (!is_readable($file)) {
            throw new \Exception("Could not read fixture file $file");
        }

        return new Calendar(file_get_contents($file));
    }

    protected function getFixtureDataFileName()
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
