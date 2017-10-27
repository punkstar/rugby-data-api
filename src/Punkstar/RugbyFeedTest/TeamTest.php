<?php

namespace Punkstar\RugbyFeedTest;

use PHPUnit\Framework\TestCase;
use Punkstar\RugbyFeed\Team;

class TeamTest extends TestCase
{
    
    public function testConstruct()
    {
        $team = new Team([
            'name' => 'Bath Rugby'
        ]);
        
        
        $this->assertEquals('Bath Rugby', $team->getName());
    }
}