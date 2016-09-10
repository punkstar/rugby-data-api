<?php

namespace Punkstar\RugbyFeed\Parser;

use PHPUnit\Framework\TestCase;
use Punkstar\RugbyFeed\Table\Row;

class BBCSportTableParserTest extends TestCase
{
    /**
     * @test
     */
    public function testExtractsRows()
    {
        $html = file_get_contents($this->getAvivaTableDataFileName());
        $parser = new BBCSportTableParser($html);

        $this->assertCount(12, $parser->getRows());
    }

    /**
     * @test
     */
    public function testRowPopulation()
    {
        $html = file_get_contents($this->getAvivaTableDataFileName());
        $parser = new BBCSportTableParser($html);

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

    /**
     * @return string
     */
    protected function getAvivaTableDataFileName()
    {
        return join(DIRECTORY_SEPARATOR, array(
            __DIR__,
            '..',
            '..',
            '..',
            '..',
            'test',
            'table',
            'bbc_sport_aviva_table.html'
        ));
    }
}