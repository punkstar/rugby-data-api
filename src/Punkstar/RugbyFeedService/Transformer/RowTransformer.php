<?php

namespace Punkstar\RugbyFeedService\Transformer;

use League\Fractal\TransformerAbstract;
use Punkstar\RugbyFeed\League;
use Punkstar\RugbyFeed\Table\Row;

class RowTransformer extends TransformerAbstract
{
    public function transform(Row $row)
    {
        return $row->toArray();
    }
}
