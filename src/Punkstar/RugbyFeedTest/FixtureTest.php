<?php

namespace Punkstar\RugbyFeedTest\Calendar;

use PHPUnit\Framework\TestCase;
use Punkstar\RugbyFeed\DataManager;
use Punkstar\RugbyFeed\Fixture;

class FixtureTest extends TestCase
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
     * @throws \Exception
     */
    public function testConstruct()
    {
        $home_team = "Northampton Saints";
        $away_team = "Gloucester Rugby";
        $home_score = 53;
        $away_score = 6;
        $kickoff = strtotime("5th September 2014");
        $league = $this->dataManager->getLeague('aviva');

        $fixture = new Fixture(
            $league,
            $home_team,
            $away_team,
            $kickoff,
            $home_score,
            $away_score,
            null
        );

        $this->assertEquals($home_team, $fixture->getHomeTeam()->getName());
        $this->assertEquals('Gloucester', $fixture->getAwayTeam()->getName());
        $this->assertEquals($home_score, $fixture->getHomeScore());
        $this->assertEquals($away_score, $fixture->getAwayScore());
        $this->assertEquals('Franklin\'s Gardens', $fixture->getLocation());
        $this->assertEquals($kickoff, $fixture->getKickoffDateTime()->getTimestamp());
    }


    /**
     * @test
     * @throws \Exception
     */
    public function testGetTeamsWithScores()
    {
        $data = array(
            'SUMMARY' => 'Northampton Saints 53 - 6 Gloucester Rugby',
            'DTSTART' => '20140905T184500Z'
        );

        $league = $this->dataManager->getLeague('aviva');

        $event = Fixture::buildFromArray($data, $league);

        $this->assertEquals('Northampton Saints', $event->getHomeTeam()->getName());
        $this->assertEquals('Gloucester', $event->getAwayTeam()->getName());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function testGetTeamsWithoutScores()
    {
        $data = array(
            'SUMMARY' => 'Newcastle Falcons v Harlequins',
            'DTSTART' => '20140905T184500Z'
        );

        $league = $this->dataManager->getLeague('aviva');
        $event = Fixture::buildFromArray($data, $league);

        $this->assertEquals('Newcastle Falcons', $event->getHomeTeam()->getName());
        $this->assertEquals('Harlequins', $event->getAwayTeam()->getName());
    }

    /**
     * @throws \Exception
     */
    public function testBtSportStripped()
    {
        $data = array(
            'SUMMARY' => 'Harlequins v Saracens BT Sport',
            'DTSTART' => '20140905T184500Z'
        );

        $league = $this->dataManager->getLeague('aviva');
        $event = Fixture::buildFromArray($data, $league);

        $this->assertEquals('Harlequins', $event->getHomeTeam()->getName());
        $this->assertEquals('Saracens', $event->getAwayTeam()->getName());
    }

    /**
     * @throws \Exception
     */
    public function testBbcNiStripped()
    {
        $data = array(
            'SUMMARY' => 'Ulster Rugby v Edinburgh Rugby BBCNI/ALBA',
            'DTSTART' => '20140905T184500Z'
        );

        $league = $this->dataManager->getLeague('pro14');
        $event = Fixture::buildFromArray($data, $league);

        $this->assertEquals('Ulster', $event->getHomeTeam()->getName());
        $this->assertEquals('Edinburgh', $event->getAwayTeam()->getName());
    }

    /**
     * @throws \Exception
     */
    public function testTg4Stripped() {
        $data = array(
            'SUMMARY' => 'Leinster Rugby v Glasgow Warriors TG4/BBC2SC',
            'DTSTART' => '20140905T184500Z'
        );

        $league = $this->dataManager->getLeague('pro14');
        $event = Fixture::buildFromArray($data, $league);

        $this->assertEquals('Leinster', $event->getHomeTeam()->getName());
        $this->assertEquals('Glasgow', $event->getAwayTeam()->getName());
    }

    /**
     * @throws \Exception
     */
    public function testBbcWStripped()
    {
        $data = array(
            'SUMMARY' => 'Cardiff Blues v Ospreys BBCW',
            'DTSTART' => '20140905T184500Z'
        );

        $league = $this->dataManager->getLeague('pro14');
        $event = Fixture::buildFromArray($data, $league);

        $this->assertEquals('Cardiff Blues', $event->getHomeTeam()->getName());
        $this->assertEquals('Ospreys', $event->getAwayTeam()->getName());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function testGetScores()
    {
        $data = array(
            'SUMMARY' => 'Newcastle Falcons 10 - 5 Harlequins',
            'DTSTART' => '20140905T184500Z'
        );

        $league = $this->dataManager->getLeague('aviva');
        $event = Fixture::buildFromArray($data, $league);

        $this->assertEquals(10, $event->getHomeScore());
        $this->assertEquals(5, $event->getAwayScore());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function testNoScoresAvailable()
    {
        $data = array(
            'SUMMARY' => 'Newcastle Falcons v Harlequins',
            'DTSTART' => '20140905T184500Z'
        );

        $league = $this->dataManager->getLeague('aviva');
        $event = Fixture::buildFromArray($data, $league);

        $this->assertNull($event->getHomeScore());
        $this->assertNull($event->getAwayScore());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function testGameFinishedYes()
    {
        $data = array(
            'SUMMARY' => 'Newcastle Falcons 10 - 5 Harlequins',
            'DTSTART' => '20140905T184500Z'
        );

        $league = $this->dataManager->getLeague('aviva');
        $event = Fixture::buildFromArray($data, $league);

        $this->assertTrue($event->isGameFinished());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function testGameFinishedNo()
    {
        $data = array(
            'SUMMARY' => 'Newcastle Falcons v Harlequins',
            'DTSTART' => '20140905T184500Z'
        );

        $league = $this->dataManager->getLeague('aviva');
        $event = Fixture::buildFromArray($data, $league);

        $this->assertFalse($event->isGameFinished());
    }

    /**
     * @test
     * @throws \Exception
     */
    public function testGetGameLocation()
    {
        $data = array(
            'SUMMARY' => 'Newcastle Falcons v Harlequins',
            'LOCATION' => 'Franklin\'s Gardens',
            'DTSTART' => '20140905T184500Z'
        );

        $league = $this->dataManager->getLeague('aviva');
        $event = Fixture::buildFromArray($data, $league);

        $this->assertEquals("Franklin's Gardens", $event->getLocation());
    }

    /**
     * @throws \Exception
     */
    public function testGetKickOff()
    {
        $data = array(
            'SUMMARY' => 'Newcastle Falcons v Harlequins',
            'DTSTART' => '20140905T184500Z'
        );

        $league = $this->dataManager->getLeague('aviva');
        $event = Fixture::buildFromArray($data, $league);

        $this->assertEquals("Fri, 05 Sep 2014 18:45:00 +0000", $event->getKickoffDateTime()->format('r'));
    }
}
