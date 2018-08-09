<?php

namespace Punkstar\RugbyFeedTest\TableProvider;

use PHPUnit\Framework\TestCase;
use Punkstar\RugbyFeed\DataManager;
use Punkstar\RugbyFeed\TableProvider\BBCSport;
use Punkstar\RugbyFeed\Table\Row;

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
    public function testExtractsRows()
    {
        $html = file_get_contents($this->getAvivaTableDataFileName());
        $league = $this->dataManager->getLeague('aviva');
        $parser = new BBCSport($html, $league);

        $this->assertCount(12, $parser->getRows());
    }

    /**
     * @test
     */
    public function testRowPopulation()
    {
        $html = file_get_contents($this->getAvivaTableDataFileName());
        $league = $this->dataManager->getLeague('aviva');
        $parser = new BBCSport($html, $league);

        /** @var Row $first_row */
        $first_row = $parser->getRows()[0];

        /** @var Row $last_row */
        $last_row = $parser->getRows()[11];

        $this->assertInstanceOf('Punkstar\RugbyFeed\Table\Row', $first_row);
        $this->assertInstanceOf('Punkstar\RugbyFeed\Table\Row', $last_row);

        $this->assertEquals(1, $first_row->position);
        $this->assertEquals('exeter', $first_row->team->getUrlKey());
        $this->assertEquals(22, $first_row->played);
        $this->assertEquals(17, $first_row->won);
        $this->assertEquals(0, $first_row->drawn);
        $this->assertEquals(5, $first_row->lost);
        $this->assertEquals(618, $first_row->for);
        $this->assertEquals(354, $first_row->against);
        $this->assertEquals(264, $first_row->points_difference);
        $this->assertEquals(17, $first_row->bonus_points);
        $this->assertEquals(85, $first_row->points);

        $this->assertEquals(12, $last_row->position);
        $this->assertEquals('london_irish', $last_row->team->getUrlKey());
        $this->assertEquals(22, $last_row->played);
        $this->assertEquals(3, $last_row->won);
        $this->assertEquals(0, $last_row->drawn);
        $this->assertEquals(19, $last_row->lost);
        $this->assertEquals(385, $last_row->for);
        $this->assertEquals(651, $last_row->against);
        $this->assertEquals(-266, $last_row->points_difference);
        $this->assertEquals(10, $last_row->bonus_points);
        $this->assertEquals(22, $last_row->points);
    }
    
    public function testForPro14()
    {
        $html = file_get_contents($this->getPro14TableDataFileName());
        $league = $this->dataManager->getLeague('pro14');
        $parser = new BBCSport($html, $league);
    
        $this->assertCount(14, $parser->getRows());
    
        /** @var Row $first_row */
        $first_row = $parser->getRows()[0];
    
        /** @var Row $last_row */
        $last_row = $parser->getRows()[13];
    
        $this->assertInstanceOf('Punkstar\RugbyFeed\Table\Row', $first_row);
        $this->assertInstanceOf('Punkstar\RugbyFeed\Table\Row', $last_row);
    
        $this->assertEquals(1, $first_row->position);
        $this->assertEquals('glasgow', $first_row->team->getUrlKey());
        $this->assertEquals(21, $first_row->played);
        $this->assertEquals(15, $first_row->won);
        $this->assertEquals(1, $first_row->drawn);
        $this->assertEquals(5, $first_row->lost);
        $this->assertEquals(614, $first_row->for);
        $this->assertEquals(366, $first_row->against);
        $this->assertEquals(248, $first_row->points_difference);
        $this->assertEquals(14, $first_row->bonus_points);
        $this->assertEquals(76, $first_row->points);
        $this->assertEquals('A', $first_row->conference);
    
        $this->assertEquals(7, $last_row->position);
        $this->assertEquals('southern_kings', $last_row->team->getUrlKey());
        $this->assertEquals(21, $last_row->played);
        $this->assertEquals(1, $last_row->won);
        $this->assertEquals(0, $last_row->drawn);
        $this->assertEquals(20, $last_row->lost);
        $this->assertEquals(378, $last_row->for);
        $this->assertEquals(829, $last_row->against);
        $this->assertEquals(-451, $last_row->points_difference);
        $this->assertEquals(7, $last_row->bonus_points);
        $this->assertEquals(11, $last_row->points);
        $this->assertEquals('B', $last_row->conference);
    }

    /**
     * @return string
     */
    protected function getAvivaTableDataFileName()
    {
        return $this->getTableFile('bbc_sport_aviva_table.html');
    }
    
    /**
     * @return string
     */
    protected function getPro14TableDataFileName()
    {
        return $this->getTableFile('bbc_sport_pro14_table.html');
    }
    
    protected function getTableFile($name)
    {
        return implode(DIRECTORY_SEPARATOR, array(
            __DIR__,
            '..',
            '..',
            '..',
            '..',
            'test',
            'table',
            $name
        ));
    }
}