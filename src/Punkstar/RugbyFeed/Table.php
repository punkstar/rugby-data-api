<?php

namespace Punkstar\RugbyFeed;

use Punkstar\RugbyFeed\Parser\BbcSportTableParser;
use Punkstar\RugbyFeed\Table\Row;

class Table
{
    private $rows;

    public function __construct(TableProvider $parser)
    {
        $this->rows = $parser->getRows();
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
            if ($a->conference == $b->conference) {
                return $a->position <=> $b->position;
            }

            return $a->conference <=> $b->conference;
        });
    }
}