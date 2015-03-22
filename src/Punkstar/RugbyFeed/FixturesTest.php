<?php

namespace Punkstar\RugbyFeed\Test;

use Punkstar\RugbyFeed\Calendar;
use Punkstar\RugbyFeed\Fixtures;
use Punkstar\RugbyFeed\Team;

class FixturesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testCorrectNumberOfEventsForTeam()
    {
        $calendar = $this->getCalendarWithAvivaFixtureData();
        $fixtures = new Fixtures($calendar);
        $bath = new Team('Bath Rugby');

        $events = $fixtures->getEventsFromTeam($bath);

        $this->assertEquals(22, count($events));
    }

    /**
     * @test
     */
    public function testGetNextFixtureStartOfSeason()
    {
        $calendar = $this->getCalendarWithAvivaFixtureData();
        $fixtures = new Fixtures($calendar);
        $bath = new Team('Bath Rugby');

        $now = \DateTime::createFromFormat('Y-m-d', '2014-09-01');

        $next_fixture = $fixtures->getNextFixture($bath, $now);

        $this->assertNotNull($next_fixture);

        $this->assertEquals($next_fixture->getHomeTeam()->name, "Sale Sharks");
        $this->assertEquals($next_fixture->getAwayTeam()->name, "Bath Rugby");
    }

    /**
     * @test
     */
    public function testGetNextFixtureMidSeason()
    {
        $calendar = $this->getCalendarWithAvivaFixtureData();
        $fixtures = new Fixtures($calendar);
        $bath = new Team('Bath Rugby');

        $now = \DateTime::createFromFormat('Y-m-d', '2014-09-12');

        $next_fixture = $fixtures->getNextFixture($bath, $now);

        $this->assertNotNull($next_fixture);

        $this->assertEquals($next_fixture->getHomeTeam()->name, "Bath Rugby");
        $this->assertEquals($next_fixture->getAwayTeam()->name, "London Welsh");
    }

    /**
     * @test
     */
    public function testGetNextFixtureMidSeasonOnDay()
    {
        $calendar = $this->getCalendarWithAvivaFixtureData();
        $fixtures = new Fixtures($calendar);
        $bath = new Team('Bath Rugby');

        $now = \DateTime::createFromFormat('Y-m-d', '2014-09-13');

        $next_fixture = $fixtures->getNextFixture($bath, $now);

        $this->assertNotNull($next_fixture);

        $this->assertEquals($next_fixture->getHomeTeam()->name, "Bath Rugby");
        $this->assertEquals($next_fixture->getAwayTeam()->name, "London Welsh");
    }

    /**
     * @test
     */
    public function testGetNextFixtureNullEndOfSeason()
    {
        $calendar = $this->getCalendarWithAvivaFixtureData();
        $fixtures = new Fixtures($calendar);
        $bath = new Team('Bath Rugby');

        $now = \DateTime::createFromFormat('Y-m-d', '2015-06-01');

        $next_fixture = $fixtures->getNextFixture($bath, $now);

        $this->assertNull($next_fixture);
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
