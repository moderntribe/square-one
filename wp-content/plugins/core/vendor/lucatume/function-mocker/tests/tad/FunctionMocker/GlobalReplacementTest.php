<?php

namespace tests\tad\FunctionMocker;


use tad\FunctionMocker\FunctionMocker as Test;

class GlobalReplacementTest extends \PHPUnit\Framework\TestCase
{

    public function setUp()
    {
        Test::setUp();
    }

    public function tearDown()
    {
        Test::tearDown();
    }

    /**
     * @test
     * it should allow replacing a global object
     */
    public function it_should_allow_replacing_a_global_object()
    {
        $GLOBALS['some'] = 200;
        $fooReplacement = Test::replaceGlobal('some', __NAMESPACE__ . '\OneClass::oneMethod', 23);

        global $some;
        $this->assertEquals(23, $some->oneMethod());
        $fooReplacement->wasCalledOnce('oneMethod');
    }

    /**
     * @test
     * it should allow replacing an unset global variable
     */
    public function it_should_allow_replacing_an_unset_global_variable()
    {
        $fooReplacement = Test::replaceGlobal('foo', __NAMESPACE__ . '\OneClass::oneMethod', 23);

        global $foo;
        $this->assertEquals(23, $foo->oneMethod());
        $fooReplacement->wasCalledOnce('oneMethod');
    }
    /**
     * @test
     * it should allow replacing a set global variable with a simple value
     */
    public function it_should_allow_replacing_a_set_global_variable_with_a_simple_value()
    {
        $GLOBALS['foo'] = 200;
        $fooReplacement = Test::setGlobal('foo', 23);

        global $foo;
        $this->assertEquals(23, $foo);
    }

    /**
     * @test
     * it should backup and restore a global variable value at tear down
     */
    public function it_should_backup_and_restore_a_global_variable_value_at_tear_down()
    {
        $GLOBALS['xyz'] = 200;
        Test::setGlobal('xyz', 23);

        global $xyz;
        $this->assertEquals(23, $xyz);

        Test::tearDown();

        $this->assertEquals(200, $GLOBALS['xyz']);
    }
}


class OneClass
{

    public function oneMethod()
    {
        return 200;
    }
}
