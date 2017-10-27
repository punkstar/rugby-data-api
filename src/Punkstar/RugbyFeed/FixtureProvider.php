<?php

namespace Punkstar\RugbyFeed;

interface FixtureProvider
{
    /**
     * @return Fixture[]
     */
    public function getFixtures();
}