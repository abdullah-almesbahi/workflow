<?php

namespace backend\lib\Ruler\Test\Fixtures;

use backend\lib\Ruler\Test\Fixtures\Fact;

class Invokable
{
    public function __invoke($value = null)
    {
        return new Fact($value);
    }
}
