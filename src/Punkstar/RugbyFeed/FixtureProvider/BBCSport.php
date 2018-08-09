<?php

namespace Punkstar\RugbyFeed\FixtureProvider;

use nokogiri;
use Punkstar\RugbyFeed\FileManager;
use Punkstar\RugbyFeed\Fixture;
use Punkstar\RugbyFeed\FixtureProvider;
use Punkstar\RugbyFeed\League;

class BBCSport implements FixtureProvider
{

    /**
     * @var string
     */
    private $html;

    /**
     * @var League
     */
    private $league;

    /**
     * BBCSport constructor.
     *
     * @param string $html
     * @param League $league
     */
    public function __construct(string $html, League $league)
    {
        $this->html = $html;
        $this->league = $league;
    }

    /**
     * Parse fixtures
     *
     * @return array|Fixture[]
     * @throws \Exception
     */
    public function getFixtures()
    {
        $document = new nokogiri($this->html);

        $dates = $document->get('[data-role="date"]');
        $fixtureGroups = $document->get('[data-role="match-group"]')->getIterator();

        $fixtures = [];
        foreach ($dates as $date) {

            $fixtureGroup = $fixtureGroups->current();

            // Loop through list of fixtures on date
            foreach ($fixtureGroup['li'] as $fixtureRow) {
                // Should have link, but may not if game is post-poned
                if (isset($fixtureRow['a'])) {
                    $fixtureRowCore = $fixtureRow['a'][0]['article'][0]['div'][0]['span'];
                } else {
                    $fixtureRowCore = $fixtureRow['article'][0]['div'][0]['span'];
                }

                if (count($fixtureRowCore) == 2) {
                    // Result
                    $home_team = $fixtureRowCore[0]['span'][0]['span'][0]['abbr'][0]['span'][0]['#text'][0];
                    $away_team = $fixtureRowCore[1]['span'][0]['span'][0]['abbr'][0]['span'][0]['#text'][0];
                    $home_score = $fixtureRowCore[0]['span'][1]['span'][0]['#text'][0];
                    $away_score = $fixtureRowCore[1]['span'][1]['span'][0]['#text'][0];
                    $kickoff = $date['#text'][0] . " (GMT) London";
                } elseif (count($fixtureRowCore) == 3) {
                    // Fixture
                    $home_team = $fixtureRowCore[0]['span'][0]['abbr'][0]['span'][0]['#text'][0];
                    $away_team = $fixtureRowCore[2]['span'][0]['abbr'][0]['span'][0]['#text'][0];
                    $kickoff = $date['#text'][0] . $fixtureRowCore[1]['span'][0]['#text'][0] . " (GMT) London";
                } else {
                    throw new \Exception("Unable to parse BBC Sport Fixtures");
                }

                $fixtures[] = new Fixture(
                    $this->league,
                    trim($home_team),
                    trim($away_team),
                    strtotime(trim($kickoff)),
                    isset($home_score) ? trim($home_score) : null,
                    isset($away_score) ? trim($away_score) : null,
                    null
                );
            }

            $fixtureGroups->next();
        }

        return $fixtures;
    }

    /**
     * Get fixture parser from URL
     *
     * @param string $url
     * @param League $league
     *
     * @throws \Exception
     * @return BBCSport
     */
    public static function fromUrl($url, $league)
    {
        $fm = new FileManager();
        return new self($fm->getFileFromUrl($url), $league);
    }
}
