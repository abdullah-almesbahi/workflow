<?php

namespace backend\lib\Ruler\Test\Operator;

use backend\lib\Ruler\Operator;
use backend\lib\Ruler\Context;
use backend\lib\Ruler\Test\Fixtures\TrueProposition;
use backend\lib\Ruler\Test\Fixtures\FalseProposition;

class LogicalAndTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $true = new TrueProposition();

        $op = new Operator\LogicalAnd(array($true));
        $this->assertInstanceOf('Ruler\Proposition', $op);
    }

    public function testConstructor()
    {
        $true    = new TrueProposition();
        $false   = new FalseProposition();
        $context = new Context();

        $op = new Operator\LogicalAnd(array($true, $false));
        $this->assertFalse($op->evaluate($context));
    }

    public function testAddPropositionAndEvaluate()
    {
        $true    = new TrueProposition();
        $false   = new FalseProposition();
        $context = new Context();

        $op = new Operator\LogicalAnd();

        $op->addProposition($true);
        $this->assertTrue($op->evaluate($context));

        $op->addOperand($true);
        $this->assertTrue($op->evaluate($context));

        $op->addProposition($false);
        $this->assertFalse($op->evaluate($context));
    }

    /**
     * @expectedException \LogicException
     */
    public function testExecutingALogicalAndWithoutPropositionsThrowsAnException()
    {
        $op = new Operator\LogicalAnd();
        $op->evaluate(new Context());
    }
}
