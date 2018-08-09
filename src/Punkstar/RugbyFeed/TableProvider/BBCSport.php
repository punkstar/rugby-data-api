<?php

namespace Punkstar\RugbyFeed\TableProvider;

use nokogiri;
use Punkstar\RugbyFeed\DataManager;
use Punkstar\RugbyFeed\FileManager;
use Punkstar\RugbyFeed\League;
use Punkstar\RugbyFeed\Table\Row;
use Punkstar\RugbyFeed\TableProvider;

class BBCSport implements TableProvider
{

    /**
     * @var string
     */
    private $html;

    /**
     * @var League
     */
    private $league;

    public function __construct($html, $league)
    {
        $this->html = $html;
        $this->league = $league;
    }

    /**
     * @return array|Row[]
     * @throws \Exception
     */
    public function getRows()
    {
        $document = new nokogiri($this->html);
        $rows = $document->get('table.gs-o-table tbody tr');

        $tableRows = [];

        foreach ($rows as $row) {
            $tableRow = new Row();

            $tableRow->position = $row['td'][0]['#text'][0];

            $tableRow->team = $this->league->getTeam($this->getTeamFromRow($row));

            if (is_null($tableRow->team)) {
                throw new \Exception("Unable to recognise team in table: " . $this->getTeamFromRow($row));
            }

            $tableRow->played = $row['td'][2]['#text'][0];
            $tableRow->won = $row['td'][3]['#text'][0];
            $tableRow->drawn = $row['td'][4]['#text'][0];
            $tableRow->lost = $row['td'][5]['#text'][0];
            $tableRow->for = $row['td'][6]['#text'][0];
            $tableRow->against = $row['td'][7]['#text'][0];
            $tableRow->points_difference = $row['td'][8]['#text'][0];
            $tableRow->bonus_points = $row['td'][9]['#text'][0];
            $tableRow->points = $row['td'][10]['#text'][0];

            if ($conference = $tableRow->team->getConference()) {
                $tableRow->conference = $conference;
            }

            $tableRows[] = $tableRow;
        }

        return $tableRows;
    }

    /**
     * @param string $url
     * @param League $league
     *
     * @throws \Exception
     * @return BBCSport
     */
    public static function fromUrl(string $url, League $league)
    {
        $fm = new FileManager();
        return new self($fm->getFileFromUrl($url), $league);
    }

    protected function getTeamFromRow($row)
    {
        $outerElement = $row['td'][1];

        if (isset($outerElement['a'])) {
            return $outerElement['a'][0]['abbr'][0]['title'];
        } elseif (isset($outerElement['abbr'])) {
            return $outerElement['abbr'][0]['title'];
        }

        return 'Unable to detect team name';
    }
}