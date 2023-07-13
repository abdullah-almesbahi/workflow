<?php

namespace backend\lib\Ruler\Test\Fixtures;

use backend\lib\Ruler\Proposition;
use backend\lib\Ruler\Context;

class CallbackProposition implements Proposition
{
    private $callback;

    public function __construct($callback)
    {
        if (!is_callable($callback)) {
            throw new \InvalidArgumentException('CallbackProposition expects a callable argument');
        }

        $this->callback = $callback;
    }

    public function evaluate(Context $context)
    {
        return call_user_func($this->callback, $context);
    }
}
