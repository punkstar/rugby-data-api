<?php

namespace Punkstar\RugbyFeedService\Transformer;

use League\Fractal\TransformerAbstract;
use Punkstar\RugbyFeed\Fixture;
use Punkstar\RugbyFeed\League;

class FixtureTransformer extends TransformerAbstract
{
    protected $league;

    public function __construct(League $league)
    {
        $this->league = $league;
    }

    public function transform(Fixture $event)
    {
        return [
            'home_team'  => $event->home_team->getName(),
            'away_team'  => $event->away_team->getName(),
            'home_score' => $event->home_score,
            'away_score' => $event->away_score,
            'location'   => $event->location,
            'kickoff'    => $event->getKickoffDateTime()->format('c'),
            'links'      => [
                [
                    'rel' => 'home_team_fixtures',
                    'uri' => sprintf(
                        "/fixtures/%s/%s",
                        $this->league->getUrlKey(),
                        $event->home_team->getUrlKey()
                    )
                ],
                [
                    'rel' => 'away_team_fixtures',
                    'uri' => sprintf(
                        "/fixtures/%s/%s",
                        $this->league->getUrlKey(),
                        $event->away_team->getUrlKey()
                    )
                ]
            ]
        ];
    }
}
