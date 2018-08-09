<?php

namespace Punkstar\RugbyFeedTest\FixtureProvider;

use PHPUnit\Framework\TestCase;
use Punkstar\RugbyFeed\DataManager;
use Punkstar\RugbyFeed\Fixture;
use Punkstar\RugbyFeed\FixtureProvider\BBCSport;
use Punkstar\RugbyFeed\League;

class BBCSportTest extends TestCase
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
     * @throws \Exception
     */
    public function testExtractAvivaFixtures()
    {
        $html = file_get_contents($this->getAvivaFixturesDataFileName());
        $parser = new BBCSport($html, $this->dataManager->getLeague('aviva'));

        $fixtures = $parser->getFixtures();
        $this->assertCount(2, $fixtures);

        /** @var Fixture $fixture */
        $fixture = $fixtures[0];

        $this->assertEquals('Saracens', $fixture->home_team->getName());
        $this->assertEquals('Wasps', $fixture->away_team->getName());
        $this->assertEquals(1526733000, $fixture->kickoff);
    }

    /**
     * @test
     */
    public function testExtractAvivaResults()
    {
        $html = file_get_contents($this->getAvivaResultsDataFileName());
        $parser = new BBCSport($html, $this->dataManager->getLeague('aviva'));

        $fixtures = $parser->getFixtures();

        $this->assertCount(133, $fixtures);

        /** @var Fixture $fixture */
        $fixture = $fixtures[0];

        $this->assertEquals('Sale Sharks', $fixture->home_team->getName());
        $this->assertEquals('Leicester Tigers', $fixture->away_team->getName());
        $this->assertEquals(13, $fixture->home_score);
        $this->assertEquals(35, $fixture->away_score);
        $this->assertEquals(1525478400, $fixture->kickoff);
    }

    /**
     * @test
     */
    public function testExtractPro14Fixtures()
    {
        $html = file_get_contents($this->getPro14FixturesDataFileName());
        $parser = new BBCSport($html, $this->dataManager->getLeague('pro14'));

        $fixtures = $parser->getFixtures();
        $this->assertCount(3, $fixtures);

        /** @var Fixture $fixture */
        $fixture = $fixtures[0];

        $this->assertEquals('Glasgow', $fixture->home_team->getName());
        $this->assertEquals('Scarlets', $fixture->away_team->getName());
        $this->assertEquals(1526601600, $fixture->kickoff);
    }

    /**
     * @test
     */
    public function testExtractPro14Results()
    {
        $html = file_get_contents($this->getPro14ResultsDataFileName());
        $parser = new BBCSport($html, $this->dataManager->getLeague('pro14'));

        $fixtures = $parser->getFixtures();

        $this->assertCount(153, $fixtures);

        /** @var Fixture $fixture */
        $fixture = $fixtures[0];

        $this->assertEquals('Glasgow', $fixture->home_team->getName());
        $this->assertEquals('Scarlets', $fixture->away_team->getName());
        $this->assertEquals(13, $fixture->home_score);
        $this->assertEquals(28, $fixture->away_score);
        $this->assertEquals(1526601600, $fixture->kickoff);
    }

    /**
     * @return string
     */
    protected function getAvivaFixturesDataFileName()
    {
        return $this->getFixtureFile('bbc_sport_aviva_fixtures.html');
    }

    /**
     * @return string
     */
    protected function getAvivaResultsDataFileName()
    {
        return $this->getFixtureFile('bbc_sport_aviva_results.html');
    }

    /**
     * @return string
     */
    protected function getPro14FixturesDataFileName()
    {
        return $this->getFixtureFile('bbc_sport_pro14_fixtures.html');
    }

    /**
     * @return string
     */
    protected function getPro14ResultsDataFileName()
    {
        return $this->getFixtureFile('bbc_sport_pro14_results.html');
    }

    protected function getFixtureFile($name)
    {
        return implode(DIRECTORY_SEPARATOR, array(
            __DIR__,
            '..',
            '..',
            '..',
            '..',
            'test',
            'fixtures',
            $name
        ));
    }
}