<?php

namespace Punkstar\RugbyFeed;

class Team
{
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getUrlKey()
    {
        return strtolower($this->name);
    }
}
