<?php

namespace Punkstar\RugbyFeedTest;

use PHPUnit\Framework\TestCase;
use Punkstar\RugbyFeed\DataManager;
use Punkstar\RugbyFeed\FixtureProvider;
use Punkstar\RugbyFeed\FixtureSet;
use Punkstar\RugbyFeed\League;
use Punkstar\RugbyFeed\Team;

class FixtureSetTest extends TestCase
{

    /**
     * @var DataManager
     */
    private $dataManager;

    /**
     * @throws \Exception
     */
    public function setUp()
    {
        $this->dataManager = new DataManager();
    }


    /**
     * @test
     */
    public function testCorrectNumberOfEventsForTeam()
    {
        $fixtures = $this->getAvivaFixtureSet();
        $bath = new Team(['name' => 'Bath Rugby', 'alias' => ['Bath']]);

        $events = $fixtures->getEventsFromTeam($bath);

        $this->assertEquals(22, count($events));
    }

    /**
     * @test
     */
    public function testGetNextFixtureStartOfSeason()
    {
        $fixtures = $this->getAvivaFixtureSet();
        $bath = new Team(['name' => 'Bath Rugby', 'alias' => ['Bath Rugby']]);

        $now = \DateTime::createFromFormat('Y-m-d', '2014-09-01');

        $next_fixture = $fixtures->getNextFixture($bath, $now);

        $this->assertNotNull($next_fixture);

        $this->assertEquals($next_fixture->getHomeTeam()->getName(), "Sale Sharks");
        $this->assertEquals($next_fixture->getAwayTeam()->getName(), "Bath Rugby");
    }

    /**
     * @test
     */
    public function testGetNextFixtureMidSeason()
    {
        $fixtures = $this->getAvivaFixtureSet();

        $bath = new Team(['name' => 'Bath Rugby', 'alias' => ['Bath']]);

        $now = \DateTime::createFromFormat('Y-m-d', '2014-09-12');

        $next_fixture = $fixtures->getNextFixture($bath, $now);

        $this->assertNotNull($next_fixture);

        $this->assertEquals($next_fixture->getHomeTeam()->getName(), "Bath Rugby");
        $this->assertEquals($next_fixture->getAwayTeam()->getName(), "London Welsh");
    }

    /**
     * @test
     */
    public function testGetNextFixtureMidSeasonOnDay()
    {
        $fixtures = $this->getAvivaFixtureSet();
        $bath = new Team(['name' => 'Bath Rugby', 'alias' => ['Bath']]);

        $now = \DateTime::createFromFormat('Y-m-d', '2014-09-13');

        $next_fixture = $fixtures->getNextFixture($bath, $now);

        $this->assertNotNull($next_fixture);

        $this->assertEquals($next_fixture->getHomeTeam()->getName(), "Bath Rugby");
        $this->assertEquals($next_fixture->getAwayTeam()->getName(), "London Welsh");
    }

    /**
     * @test
     */
    public function testGetNextFixtureNullEndOfSeason()
    {
        $fixtures = $this->getAvivaFixtureSet();
        $bath = new Team(['name' => 'Bath Rugby', 'alias' => ['Bath']]);

        $now = \DateTime::createFromFormat('Y-m-d', '2015-06-01');

        $next_fixture = $fixtures->getNextFixture($bath, $now);

        $this->assertNull($next_fixture);
    }

    protected function getAvivaFixtureSet()
    {
        $file = $this->getAvivaFixtureDataFileName();

        if (!file_exists($file)) {
            throw new \Exception("Could not load fixture file $file");
        }

        if (!is_readable($file)) {
            throw new \Exception("Could not read fixture file $file");
        }

        $league = $this->dataManager->getLeague('aviva');

        return new FixtureSet([new FixtureProvider\ICal(file_get_contents($file), $league)]);
    }

    protected function getAvivaFixtureDataFileName()
    {
        return implode(DIRECTORY_SEPARATOR, array(
            __DIR__,
            '..',
            '..',
            '..',
            'test',
            'fixtures',
            'example.ics'
        ));
    }

    protected function getPro14FixtureSet()
    {
        $file = $this->getPro14FixtureDataFileName();

        if (!file_exists($file)) {
            throw new \Exception("Could not load fixture file $file");
        }

        if (!is_readable($file)) {
            throw new \Exception("Could not read fixture file $file");
        }

        return new FixtureSet([new FixtureProvider\ICal(file_get_contents($file))]);
    }

    protected function getPro14FixtureDataFileName()
    {
        return implode(DIRECTORY_SEPARATOR, array(
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
