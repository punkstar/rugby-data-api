<?php

namespace Punkstar\RugbyFeed\TableProvider;

use Punkstar\RugbyFeed\Table\Row;
use Punkstar\RugbyFeed\TableProvider;

class Basic implements TableProvider
{
    
    /**
     * @return Row[]
     */
    public function getRows()
    {
        return [];
    }
}