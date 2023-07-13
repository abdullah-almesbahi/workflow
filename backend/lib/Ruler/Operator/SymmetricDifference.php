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
use backend\lib\Ruler\Set;
use backend\lib\Ruler\VariableOperand;

/**
 * A Symmetric Difference Set Operator
 *
 * @author Jordan Raub <jordan@raub.me>
 */
class SymmetricDifference extends VariableOperator implements VariableOperand
{
    public function prepareValue(Context $context)
    {
        /** @var VariableOperand $left */
        /** @var VariableOperand $right */
        list($left, $right) = $this->getOperands();

        return $left->prepareValue($context)->getSet()
            ->symmetricDifference($right->prepareValue($context)->getSet());
    }

    protected function getOperandCardinality()
    {
        return static::BINARY;
    }
}
