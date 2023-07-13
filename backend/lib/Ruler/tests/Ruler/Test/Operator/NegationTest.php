<?php

namespace backend\lib\Ruler\Test\Operator;

use backend\lib\Ruler\Operator;
use backend\lib\Ruler\Context;
use backend\lib\Ruler\Variable;

class NegationTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 1);

        $op = new Operator\Negation($varA);
        $this->assertInstanceOf('Ruler\\VariableOperand', $op);
    }

    /**
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Arithmetic: values must be numeric
     */
    public function testInvalidData()
    {
        $varA    = new Variable('a', "string");
        $context = new Context();

        $op = new Operator\Negation($varA);
        $op->prepareValue($context);
    }

    /**
     * @dataProvider negateData
     */
    public function testSubtract($a, $result)
    {
        $varA    = new Variable('a', $a);
        $context = new Context();

        $op = new Operator\Negation($varA);
        $this->assertEquals($op->prepareValue($context)->getValue(), $result);
    }

    public function negateData()
    {
        return array(
            array(1, -1),
            array(0.0, 0.0),
            array("0", 0),
            array(-62834, 62834),
        );
    }
}
