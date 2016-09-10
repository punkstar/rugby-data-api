<?php

namespace Punkstar\RugbyFeed;

use PHPUnit\Framework\TestCase;
use Punkstar\RugbyFeed\Table\Row;

class TeamTest extends TestCase
{
    /**
     * @test
     */
    public function testFromTeamMap()
    {
        $this->assertEquals('Bath Rugby', Team::build('bath')->name);
    }

    /**
     * @test
     */
    public function testNotFoundInTeamMap()
    {
        $this->assertEquals('Meh', Team::build('meh')->name);
    }
}