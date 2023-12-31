<?php

/*
 * This file is part of the Ruler package, an OpenSky project.
 *
 * (c) 2011 OpenSky Project Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace backend\lib\Ruler\Operator;

use backend\lib\Ruler\Context;
use backend\lib\Ruler\Proposition;
use backend\lib\Ruler\VariableOperand;

/**
 * A String does not Contain case insensitive comparison operator.
 *
 * @author Jordan Raub <jordan@raub.me>
 */
class StringDoesNotContainInsensitive extends VariableOperator implements Proposition
{
    /**
     * @param Context $context Context with which to evaluate this Proposition
     *
     * @return boolean
     */
    public function evaluate(Context $context)
    {
        /** @var VariableOperand $left */
        /** @var VariableOperand $right */
        list($left, $right) = $this->getOperands();

        return $left->prepareValue($context)->stringContainsInsensitive($right->prepareValue($context)) === false;
    }

    protected function getOperandCardinality()
    {
        return static::BINARY;
    }
}
