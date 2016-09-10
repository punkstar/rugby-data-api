<?php

namespace Punkstar\RugbyFeed;

use PHPUnit\Framework\TestCase;
use Punkstar\RugbyFeed\Table\Row;

class TableTest extends TestCase
{
    /**
     * @test
     */
    public function testAddRow()
    {
        $row = new Row();
        $table = new Table();

        $table->addRow($row);

        $this->assertCount(1, $table->getRows());
    }

    /**
     * @test
     */
    public function testGetRowsOrderByPosition()
    {
        $first = new Row();
        $second = new Row();
        $third = new Row();


        $first->position  = 1;
        $second->position = 2;
        $third->position  = 3;

        $table = new Table();
        $table->addRow($second);
        $table->addRow($first);

        $this->assertEquals(1, $table->getRows()[0]->position);
        $this->assertEquals(2, $table->getRows()[1]->position);

        $table->addRow($third);

        $this->assertEquals(1, $table->getRows()[0]->position);
        $this->assertEquals(2, $table->getRows()[1]->position);
        $this->assertEquals(3, $table->getRows()[2]->position);
    }
}