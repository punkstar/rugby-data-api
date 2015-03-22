<?php

namespace Punkstar\RugbyFeed\Calendar;

class EventTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testGetTeamsWithScores()
    {
        $data = array(
            'SUMMARY' => 'Northampton Saints 53 - 6 Gloucester Rugby'
        );

        $event = Event::buildFromArray($data);

        $this->assertEquals('Northampton Saints', $event->getHomeTeam());
        $this->assertEquals('Gloucester Rugby', $event->getAwayTeam());
    }

    /**
     * @test
     */
    public function testGetTeamsWithoutScores()
    {
        $data = array(
            'SUMMARY' => 'Newcastle Falcons v Harlequins'
        );

        $event = Event::buildFromArray($data);

        $this->assertEquals('Newcastle Falcons', $event->getHomeTeam());
        $this->assertEquals('Harlequins', $event->getAwayTeam());
    }

    public function testBtSportStripped()
    {
        $data = array(
            'SUMMARY' => 'Harlequins v Saracens BT Sport'
        );

        $event = Event::buildFromArray($data);

        $this->assertEquals('Harlequins', $event->getHomeTeam());
        $this->assertEquals('Saracens', $event->getAwayTeam());
    }

    /**
     * @test
     */
    public function testGetScores()
    {
        $data = array(
            'SUMMARY' => 'Newcastle Falcons 10 - 5 Harlequins'
        );

        $event = Event::buildFromArray($data);

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

        $event = Event::buildFromArray($data);

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

        $event = Event::buildFromArray($data);

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

        $event = Event::buildFromArray($data);

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

        $event = Event::buildFromArray($data);

        $this->assertEquals("Franklin's Gardens", $event->getLocation());
    }

    public function testGetKickOff()
    {
        $data = array(
            'DTSTART' => '20140905T184500Z'
        );

        $event = Event::buildFromArray($data);

        $this->assertEquals("Fri, 05 Sep 2014 18:45:00 +0000", $event->getKickoffDateTime()->format('r'));
    }
}
