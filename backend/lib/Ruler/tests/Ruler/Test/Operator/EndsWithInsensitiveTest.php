<?php

namespace backend\lib\Ruler\Test\Operator;

use backend\lib\Ruler\Context;
use backend\lib\Ruler\Operator;
use backend\lib\Ruler\Variable;

class EndsWithInsensitiveTest extends \PHPUnit_Framework_TestCase
{
    public function testInterface()
    {
        $varA = new Variable('a', 'foo bar baz');
        $varB = new Variable('b', 'foo');

        $op = new Operator\StartsWith($varA, $varB);
        $this->assertInstanceOf('Ruler\Proposition', $op);
    }

    /**
     * @dataProvider endsWithData
     */
    public function testEndsWithInsensitive($a, $b, $result)
    {
        $varA    = new Variable('a', $a);
        $varB    = new Variable('b', $b);
        $context = new Context();

        $op = new Operator\EndsWithInsensitive($varA, $varB);
        $this->assertEquals($op->evaluate($context), $result);
    }

    public function endsWithData()
    {
        return array(
            array('supercalifragilistic', 'supercalifragilistic', true),
            array('supercalifragilistic', 'stic', true),
            array('supercalifragilistic', 'STIC', true),
            array('supercalifragilistic', 'super', false),
            array('supercalifragilistic', '', false),
        );
    }
}
