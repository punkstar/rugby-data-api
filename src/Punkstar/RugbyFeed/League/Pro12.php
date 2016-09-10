<?php

namespace Punkstar\RugbyFeed\League;

class Pro12 extends AbstractLeague
{
    protected $calendar_url = 'http://www.pro12rugby.com/tools/calendars/celtic-fixtures.ics?v=142704';
    protected $table_url = 'http://www.bbc.co.uk/sport/rugby-union/pro12/table';
    protected $url_key = 'pro12';

    protected $teams = array(
        'Munster Rugby',
        'Edinburgh Rugby',
        'Ospreys',
        'Benetton Treviso',
        'Scarlets',
        'Ulster Rugby',
        'Connacht Rugby',
        'Newport Gwent Dragons',
        'Glasgow Warriors',
        'Leinster Rugby',
        'Zebre',
        'Cardiff Blues'
    );
}
