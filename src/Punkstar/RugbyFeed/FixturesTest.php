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
        $calendar = $this->getCalendarWithFixtureData();
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
        $calendar = $this->getCalendarWithFixtureData();
        $fixtures = new Fixtures($calendar);
        $bath = new Team('Bath Rugby');

        $now = \DateTime::createFromFormat('Y-m-d', '2014-09-01');

        $next_fixture = $fixtures->getNextFixture($bath, $now);

        $this->assertNotNull($next_fixture);

        $this->assertEquals($next_fixture->getHomeTeam(), "Sale Sharks");
        $this->assertEquals($next_fixture->getAwayTeam(), "Bath Rugby");
    }

    /**
     * @test
     */
    public function testGetNextFixtureMidSeason()
    {
        $calendar = $this->getCalendarWithFixtureData();
        $fixtures = new Fixtures($calendar);
        $bath = new Team('Bath Rugby');

        $now = \DateTime::createFromFormat('Y-m-d', '2014-09-12');

        $next_fixture = $fixtures->getNextFixture($bath, $now);

        $this->assertNotNull($next_fixture);

        $this->assertEquals($next_fixture->getHomeTeam(), "Bath Rugby");
        $this->assertEquals($next_fixture->getAwayTeam(), "London Welsh");
    }

    /**
     * @test
     */
    public function testGetNextFixtureMidSeasonOnDay()
    {
        $calendar = $this->getCalendarWithFixtureData();
        $fixtures = new Fixtures($calendar);
        $bath = new Team('Bath Rugby');

        $now = \DateTime::createFromFormat('Y-m-d', '2014-09-13');

        $next_fixture = $fixtures->getNextFixture($bath, $now);

        $this->assertNotNull($next_fixture);

        $this->assertEquals($next_fixture->getHomeTeam(), "Bath Rugby");
        $this->assertEquals($next_fixture->getAwayTeam(), "London Welsh");
    }

    /**
     * @test
     */
    public function testGetNextFixtureNullEndOfSeason()
    {
        $calendar = $this->getCalendarWithFixtureData();
        $fixtures = new Fixtures($calendar);
        $bath = new Team('Bath Rugby');

        $now = \DateTime::createFromFormat('Y-m-d', '2015-06-01');

        $next_fixture = $fixtures->getNextFixture($bath, $now);

        $this->assertNull($next_fixture);
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
