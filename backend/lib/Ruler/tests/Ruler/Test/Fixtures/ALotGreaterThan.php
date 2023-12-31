<?php

/*
 * This file is part of the Ruler package, an OpenSky project.
 *
 * (c) 2011 OpenSky Project Inc
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace backend\lib\Ruler\Test\Fixtures;

use backend\lib\Ruler\Context;
use backend\lib\Ruler\Operator\VariableOperator;
use backend\lib\Ruler\Proposition;
use backend\lib\Ruler\Value;
use backend\lib\Ruler\VariableOperand;

/**
 * An EqualTo comparison operator.
 *
 * @author Justin Hileman <justin@shopopensky.com>
 */
class ALotGreaterThan extends VariableOperator implements Proposition
{
    /**
     * Evaluate whether the given variables are equal in the current Context.
     *
     * @param Context $context Context with which to evaluate this ComparisonOperator
     *
     * @return boolean
     */
    public function evaluate(Context $context)
    {
        /** @var VariableOperand $left */
        /** @var VariableOperand $right */
        list($left, $right) = $this->getOperands();
        $value = $right->prepareValue($context)->getValue() * 10;

        return $left->prepareValue($context)->greaterThan(new Value($value));
    }

    protected function getOperandCardinality()
    {
        return static::BINARY;
    }
}
