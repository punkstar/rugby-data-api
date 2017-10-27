<?php

namespace Punkstar\RugbyFeed;

use Punkstar\RugbyFeed\Table\Row;

interface TableProvider
{
    /**
     * @return Row[]
     */
    public function getRows();
}