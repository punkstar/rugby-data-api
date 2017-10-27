<?php

namespace Punkstar\RugbyFeedTest\TableProvider;

use PHPUnit\Framework\TestCase;
use Punkstar\RugbyFeed\TableProvider\BBCSport;
use Punkstar\RugbyFeed\Table\Row;

class BBCSportTest extends TestCase
{
    /**
     * @test
     */
    public function testExtractsRows()
    {
        $html = file_get_contents($this->getAvivaTableDataFileName());
        $parser = new BBCSport($html);

        $this->assertCount(12, $parser->getRows());
    }

    /**
     * @test
     */
    public function testRowPopulation()
    {
        $html = file_get_contents($this->getAvivaTableDataFileName());
        $parser = new BBCSport($html);

        /** @var Row $first_row */
        $first_row = $parser->getRows()[0];

        /** @var Row $last_row */
        $last_row = $parser->getRows()[11];

        $this->assertInstanceOf('Punkstar\RugbyFeed\Table\Row', $first_row);
        $this->assertInstanceOf('Punkstar\RugbyFeed\Table\Row', $last_row);

        $this->assertEquals(1, $first_row->position);
        $this->assertEquals('saracens', $first_row->team);
        $this->assertEquals(1, $first_row->played);
        $this->assertEquals(1, $first_row->won);
        $this->assertEquals(0, $first_row->drawn);
        $this->assertEquals(0, $first_row->lost);
        $this->assertEquals(35, $first_row->for);
        $this->assertEquals(3, $first_row->against);
        $this->assertEquals(1, $first_row->bonus_points);
        $this->assertEquals(5, $first_row->points);

        $this->assertEquals(12, $last_row->position);
        $this->assertEquals('worcester', $last_row->team);
        $this->assertEquals(1, $last_row->played);
        $this->assertEquals(0, $last_row->won);
        $this->assertEquals(0, $last_row->drawn);
        $this->assertEquals(1, $last_row->lost);
        $this->assertEquals(3, $last_row->for);
        $this->assertEquals(35, $last_row->against);
        $this->assertEquals(0, $last_row->bonus_points);
        $this->assertEquals(0, $last_row->points);
    }
    
    public function testForPro14()
    {
        $html = file_get_contents($this->getPro14TableDataFileName());
        $parser = new BBCSport($html);
    
        $this->assertCount(14, $parser->getRows());
    
        /** @var Row $first_row */
        $first_row = $parser->getRows()[0];
    
        /** @var Row $last_row */
        $last_row = $parser->getRows()[13];
    
        $this->assertInstanceOf('Punkstar\RugbyFeed\Table\Row', $first_row);
        $this->assertInstanceOf('Punkstar\RugbyFeed\Table\Row', $last_row);
    
        $this->assertEquals(1, $first_row->position);
        $this->assertEquals('glasgow', $first_row->team);
        $this->assertEquals(6, $first_row->played);
        $this->assertEquals(6, $first_row->won);
        $this->assertEquals(0, $first_row->drawn);
        $this->assertEquals(0, $first_row->lost);
        $this->assertEquals(172, $first_row->for);
        $this->assertEquals(98, $first_row->against);
        $this->assertEquals(4, $first_row->bonus_points);
        $this->assertEquals(28, $first_row->points);
    
        $this->assertEquals(7, $last_row->position);
        $this->assertEquals('southern kings', $last_row->team);
        $this->assertEquals(6, $last_row->played);
        $this->assertEquals(0, $last_row->won);
        $this->assertEquals(0, $last_row->drawn);
        $this->assertEquals(6, $last_row->lost);
        $this->assertEquals(63, $last_row->for);
        $this->assertEquals(223, $last_row->against);
        $this->assertEquals(0, $last_row->bonus_points);
        $this->assertEquals(0, $last_row->points);
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