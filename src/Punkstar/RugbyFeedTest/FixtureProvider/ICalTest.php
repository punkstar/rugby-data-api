<?php

namespace Punkstar\RugbyFeedTest;

use PHPUnit\Framework\TestCase;
use Punkstar\RugbyFeed\FixtureProvider\ICal;

class ICalTest extends TestCase
{
    /**
     * @test
     */
    public function testCorrectNumberOfEvents()
    {
        $calendar = $this->getCalendarWithAvivaFixtureData();
        $this->assertEquals(132, count($calendar->getFixtures()));
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

        return new ICal(file_get_contents($file));
    }

    protected function getAvivaFixtureDataFileName()
    {
        return implode(DIRECTORY_SEPARATOR, array(
            __DIR__,
            '..',
            '..',
            '..',
            '..',
            'test',
            'fixtures',
            'example.ics'
        ));
    }
}
