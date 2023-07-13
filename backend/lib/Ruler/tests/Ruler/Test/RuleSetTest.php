<?php

namespace backend\lib\Ruler\Test;

use backend\lib\Ruler\RuleSet;
use backend\lib\Ruler\Rule;
use backend\lib\Ruler\Context;
use backend\lib\Ruler\Test\Fixtures\TrueProposition;

class RuleSetTest extends \PHPUnit_Framework_TestCase
{
    public function testRulesetCreationUpdateAndExecution()
    {
        $context = new Context();
        $true    = new TrueProposition();

        $executedActionA = false;
        $ruleA = new Rule($true, function () use (&$executedActionA) {
            $executedActionA = true;
        });

        $executedActionB = false;
        $ruleB = new Rule($true, function () use (&$executedActionB) {
            $executedActionB = true;
        });

        $executedActionC = false;
        $ruleC = new Rule($true, function () use (&$executedActionC) {
            $executedActionC = true;
        });

        $ruleset = new RuleSet(array($ruleA));

        $ruleset->executeRules($context);

        $this->assertTrue($executedActionA);
        $this->assertFalse($executedActionB);
        $this->assertFalse($executedActionC);

        $ruleset->addRule($ruleC);
        $ruleset->executeRules($context);

        $this->assertTrue($executedActionA);
        $this->assertFalse($executedActionB);
        $this->assertTrue($executedActionC);
    }
}
