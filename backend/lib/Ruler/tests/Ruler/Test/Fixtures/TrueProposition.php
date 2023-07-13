<?php

namespace backend\lib\Ruler\Test\Fixtures;

use backend\lib\Ruler\Proposition;
use backend\lib\Ruler\Context;

class TrueProposition implements Proposition
{
    public function evaluate(Context $context)
    {
        return true;
    }
}
