<?php

namespace Punkstar\RugbyFeed\League;

class Aviva extends AbstractLeague
{
    protected $calendar_url = 'http://www.premiershiprugby.com/tools/calendars/premier-premiership.ics?v=142610';

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
        'London Welsh'
    );
}
