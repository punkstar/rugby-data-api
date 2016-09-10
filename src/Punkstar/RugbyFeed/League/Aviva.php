<?php

namespace Punkstar\RugbyFeed\League;

class Aviva extends AbstractLeague
{
    protected $calendar_url = 'http://www.premiershiprugby.com/tools/calendars/premier-premiership.ics?v=142610';
    protected $table_url = 'http://www.bbc.co.uk/sport/rugby-union/english-premiership/table';
    protected $url_key = 'aviva';

    protected $teams = array(
        'Northampton Saints',
        'Exeter Chiefs',
        'Saracens',
        'Bath Rugby',
        'Leicester Tigers',
        'Wasps',
        'Sale Sharks',
        'Harlequins',
        'Gloucester Rugby',
        'Newcastle Falcons',
        'Bristol Rugby',
        'Worcester Warriors'
    );
}
