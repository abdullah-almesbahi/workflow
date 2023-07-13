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
use backend\lib\Ruler\Value;
use backend\lib\Ruler\VariableOperand;

/**
 * A set min operator
 *
 * @author Jordan Raub <jordan@raub.me>
 */
class Min extends VariableOperator implements VariableOperand
{
    public function prepareValue(Context $context)
    {
        /** @var VariableOperand $operand */
        list($operand) = $this->getOperands();

        return new Value($operand->prepareValue($context)->getSet()->min());
    }

    protected function getOperandCardinality()
    {
        return static::UNARY;
    }
}
