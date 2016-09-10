# Rugby Data API

[![Build Status](https://travis-ci.org/punkstar/rugby-data-api.svg?branch=master)](https://travis-ci.org/punkstar/rugby-data-api)

This repository provides an API for accessing a variety of Rugby fixture and result data.

## Development

To run the project locally, use the inbuilt web server:

    php -S 0.0.0.0:4000 -t public/

To run tests:

    ./vendor/bin/phpunit

## Models

### Fixture

    {
        home_team: {
            name: "Gloucester Rugby"
        },
        away_team: {
            name: "Leicester Tigers"
        },

        home_score: "31",
        away_score: "38",
        location: "Kingsholm",
        kickoff: "2016-09-02T18:45:00+00:00",

        links: [{
            rel: "home_team_fixtures",
            uri: "/fixtures/aviva/gloucester rugby"
        }, {
            rel: "away_team_fixtures",
            uri: "/fixtures/aviva/leicester tigers"
        }]
    }

### Table Row

    {
        position: "1",
        team: "bath",
        played: "2",
        won: "2",
        drawn: "0",
        lost: "0",
        for: "76",
        against: "19",
        bonus_points: "1",
        points: "9"
    }

## Endpoints

### League Table (/table/{league})

Available leagues:

* `aviva`
* `pro12`

#### Response

    {
        data: [
            <Table Row />,
            <Table Row />,
            ...
        ]
    }

### League Fixtures (/fixtures/{league})

Available leagues:

* `aviva`
* `pro12`

#### Response

    {
        data: [
            <Fixture />,
            <Fixture />,
            ...
        ]
    }

### Team Fixtures in a League (/fixtures/{league}/{team})


Available teams:

* `aviva`
    * `northampton Saints`
    * `exeter chiefs`
    * `saracens`
    * `bath rugby`
    * `leicester tigers`
    * `wasps`
    * `sale sharks`
    * `harlequins`
    * `gloucester rugby`
    * `newcastle falcons`
    * `bristol rugby`
* `pro12`
    * `munster rugby`
    * `edinburgh rugby`
    * `ospreys`
    * `benetton treviso`
    * `scarlets`
    * `ulster rugby`
    * `connacht rugby`
    * `newport gwent dragons`
    * `glasgow warriors`
    * `leinster rugby`
    * `zebre`
    * `cardiff blues`


#### Response

    {
        data: [
            <Fixture />,
            <Fixture />,
            ...
        ]
    }