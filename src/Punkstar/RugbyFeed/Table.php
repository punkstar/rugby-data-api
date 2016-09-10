<?php

namespace Punkstar\RugbyFeed;

use Punkstar\RugbyFeed\Table\Row;

class Table
{
    private $rows;

    public function __construct($rows = [])
    {
        $this->rows = $rows;
    }

    /**
     * @param Row $row
     */
    public function addRow(Row $row)
    {
        $this->rows[] = $row;
    }

    /**
     * @return Row[]
     */
    public function getRows()
    {
        $this->sort();
        return $this->rows;
    }

    /**
     * @return bool
     */
    protected function sort()
    {
        return usort($this->rows, function ($a, $b) {
            return $a->position <=> $b->position;
        });
    }
}