<?php

namespace Punkstar\RugbyFeed\TableProvider;

use nokogiri;
use Punkstar\RugbyFeed\DataManager;
use Punkstar\RugbyFeed\FileManager;
use Punkstar\RugbyFeed\Table\Row;
use Punkstar\RugbyFeed\TableProvider;

class BBCSport implements TableProvider
{
    private $html;
    /**
     * @var null
     */
    private $dataManager;
    
    public function __construct($html, $dataManager = null)
    {
        $this->html = $html;
        $this->dataManager = $dataManager ?? new DataManager();
    }

    public function getRows()
    {
        $document = new nokogiri($this->html);
        $rows = $document->get('#rugby-competition-table tbody tr');

        $tableRows = [];

        foreach ($rows as $row) {
            $tableRow = new Row();

            $tableRow->position = $row['th'][0]['#text'][0];
            
            $tableRow->team = $this->dataManager->getTeam($this->getTeamFromRow($row));

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
     * @return BBCSport
     */
    public static function fromUrl($url, $dataManager = null)
    {
        $fm = new FileManager();
        return new self($fm->getFileFromUrl($url), $dataManager);
    }
    
    protected function getTeamFromRow($row) {
        $outerElement = $row['th'][1];
    
        if (isset($outerElement['a'])) {
            return $outerElement['a'][0]['title'];
        }
    
        if (isset($outerElement['span'])) {
            return $outerElement['span'][0]['#text'][0];
        }
    
        return 'Unable to detect team name';
    }
}