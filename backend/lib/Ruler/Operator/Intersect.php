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
 * A Set Intersection Operator
 *
 * @author Jordan Raub <jordan@raub.me>
 */
class Intersect extends VariableOperator implements VariableOperand
{
    public function prepareValue(Context $context)
    {
        $intersect = null;
        /** @var VariableOperand $operand */
        foreach ($this->getOperands() as $operand) {
            if (!$intersect instanceof Set) {
                $intersect = $operand->prepareValue($context)->getSet();
            } else {
                $set = $operand->prepareValue($context)->getSet();
                $intersect = $intersect->intersect($set);
            }
        }

        return $intersect;
    }

    protected function getOperandCardinality()
    {
        return static::MULTIPLE;
    }
}
