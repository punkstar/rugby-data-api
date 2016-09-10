<?php

namespace Punkstar\RugbyFeed\Parser;

use nokogiri;
use Punkstar\RugbyFeed\FileManager;
use Punkstar\RugbyFeed\Table\Row;

class BBCSportTableParser
{
    private $html;

    public function __construct($html)
    {
        $this->html = $html;
    }

    public function getRows()
    {
        $document = new nokogiri($this->html);
        $rows = $document->get('#rugby-competition-table tbody tr');

        $tableRows = [];

        foreach ($rows as $row) {
            $tableRow = new Row();

            $tableRow->position = $row['th'][0]['#text'][0];
            $tableRow->team = strtolower($row['th'][1]['a'][0]['title']);

            $tableRow->played = $row['td'][0]['#text'][0];
            $tableRow->won = $row['td'][1]['#text'][0];
            $tableRow->drawn = $row['td'][2]['#text'][0];
            $tableRow->lost = $row['td'][3]['#text'][0];
            $tableRow->for = $row['td'][4]['#text'][0];
            $tableRow->against = $row['td'][5]['#text'][0];
            $tableRow->bonus_points = $row['td'][6]['#text'][0];
            $tableRow->points = $row['td'][7]['#text'][0];

            $tableRows[] = $tableRow;
        }

        return $tableRows;
    }

    /**
     * @param $url
     * @throws \Exception
     * @return BBCSportTableParser
     */
    public static function fromUrl($url)
    {
        $fm = new FileManager();
        return new self($fm->getFileFromUrl($url));
    }
}