<?php

namespace Punkstar\RugbyFeedTest\Calendar;

use PHPUnit\Framework\TestCase;
use Punkstar\RugbyFeed\Fixture;

class FixtureTest extends TestCase
{
    /**
     * @test
     */
    public function testGetTeamsWithScores()
    {
        $data = array(
            'SUMMARY' => 'Northampton Saints 53 - 6 Gloucester Rugby'
        );

        $event = Fixture::buildFromArray($data);

        $this->assertEquals('Northampton Saints', $event->getHomeTeam()->getName());
        $this->assertEquals('Gloucester', $event->getAwayTeam()->getName());
    }

    /**
     * @test
     */
    public function testGetTeamsWithoutScores()
    {
        $data = array(
            'SUMMARY' => 'Newcastle Falcons v Harlequins'
        );

        $event = Fixture::buildFromArray($data);

        $this->assertEquals('Newcastle Falcons', $event->getHomeTeam()->getName());
        $this->assertEquals('Harlequins', $event->getAwayTeam()->getName());
    }

    public function testBtSportStripped()
    {
        $data = array(
            'SUMMARY' => 'Harlequins v Saracens BT Sport'
        );

        $event = Fixture::buildFromArray($data);

        $this->assertEquals('Harlequins', $event->getHomeTeam()->getName());
        $this->assertEquals('Saracens', $event->getAwayTeam()->getName());
    }

    public function testBbcNiStripped()
    {
        $data = array(
            'SUMMARY' => 'Ulster Rugby v Edinburgh Rugby BBCNI/ALBA'
        );

        $event = Fixture::buildFromArray($data);

        $this->assertEquals('Ulster', $event->getHomeTeam()->getName());
        $this->assertEquals('Edinburgh', $event->getAwayTeam()->getName());
    }

    public function testTg4Stripped() {
        $data = array(
            'SUMMARY' => 'Leinster Rugby v Glasgow Warriors TG4/BBC2SC'
        );

        $event = Fixture::buildFromArray($data);

        $this->assertEquals('Leinster', $event->getHomeTeam()->getName());
        $this->assertEquals('Glasgow', $event->getAwayTeam()->getName());
    }

    public function testBbcWStripped()
    {
        $data = array(
            'SUMMARY' => 'Cardiff Blues v Ospreys BBCW'
        );

        $event = Fixture::buildFromArray($data);

        $this->assertEquals('Cardiff Blues', $event->getHomeTeam()->getName());
        $this->assertEquals('Ospreys', $event->getAwayTeam()->getName());
    }

    /**
     * @test
     */
    public function testGetScores()
    {
        $data = array(
            'SUMMARY' => 'Newcastle Falcons 10 - 5 Harlequins'
        );

        $event = Fixture::buildFromArray($data);

        $this->assertEquals(10, $event->getHomeScore());
        $this->assertEquals(5, $event->getAwayScore());
    }

    /**
     * @test
     */
    public function testNoScoresAvailable()
    {
        $data = array(
            'SUMMARY' => 'Newcastle Falcons v Harlequins'
        );

        $event = Fixture::buildFromArray($data);

        $this->assertNull($event->getHomeScore());
        $this->assertNull($event->getAwayScore());
    }

    /**
     * @test
     */
    public function testGameFinishedYes()
    {
        $data = array(
            'SUMMARY' => 'Newcastle Falcons 10 - 5 Harlequins'
        );

        $event = Fixture::buildFromArray($data);

        $this->assertTrue($event->isGameFinished());
    }

    /**
     * @test
     */
    public function testGameFinishedNo()
    {
        $data = array(
            'SUMMARY' => 'Newcastle Falcons v Harlequins'
        );

        $event = Fixture::buildFromArray($data);

        $this->assertFalse($event->isGameFinished());
    }

    /**
     * @test
     */
    public function testGetGameLocation()
    {
        $data = array(
            'LOCATION' => 'Franklin\'s Gardens'
        );

        $event = Fixture::buildFromArray($data);

        $this->assertEquals("Franklin's Gardens", $event->getLocation());
    }

    public function testGetKickOff()
    {
        $data = array(
            'DTSTART' => '20140905T184500Z'
        );

        $event = Fixture::buildFromArray($data);

        $this->assertEquals("Fri, 05 Sep 2014 18:45:00 +0000", $event->getKickoffDateTime()->format('r'));
    }
}
