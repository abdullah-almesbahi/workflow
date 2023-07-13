<?php

namespace backend\lib\Ruler\Test\Fixtures;

use backend\lib\Ruler\Proposition;
use backend\lib\Ruler\Context;

class FalseProposition implements Proposition
{
    public function evaluate(Context $context)
    {
        return false;
    }
}
