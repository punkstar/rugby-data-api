<?php

namespace Punkstar\RugbyFeed;

interface League
{
    public function getCalendarUrl();
    public function getTeams();
    public function getCalendar();
    public function getUrlKey();
}
