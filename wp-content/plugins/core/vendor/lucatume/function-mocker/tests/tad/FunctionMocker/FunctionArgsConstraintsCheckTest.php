<?php

namespace tad\FunctionMocker\Tests;

use tad\FunctionMocker\FunctionMocker as Sut;

class FunctionArgsConstraintsCheckTest extends TestCase
{

    public function setUp()
    {
        Sut::setUp();
    }

    public function tearDown()
    {
        Sut::tearDown();
    }

    public function callArgsAndConstraints()
    {
        return [
            ['foo', 'foo', true],
            ['foo', Sut::isType('string'), true],
            ['foo', Sut::isType('array'), false],
            [new \stdClass(), Sut::isInstanceOf('\stdClass'), true],
            ['foo', Sut::isInstanceOf('\stdClass'), false]
        ];
    }

    /**
     * @test
     * it should allow verifying the type of args a function is called with
     * @dataProvider callArgsAndConstraints
     */
    public function it_should_allow_verifying_the_type_of_args_a_function_is_called_with($callArg, $expectedArg, $shouldPass)
    {
        if (!$shouldPass) {
            $this->expectFailure();
        }
        $func = Sut::replace(__NAMESPACE__ . '\alpha');

        alpha($callArg);

        $func->wasCalledWithOnce([$expectedArg]);
    }

    public function multipleCallArgsAndConstraints()
    {
        return [
            [['foo', 'foo'], ['foo', 'foo'], true],
            [['foo', 'foo'], [Sut::isType('string'), 'foo'], true],
            [['foo', 'foo'], ['foo', Sut::isType('string')], true],
            [['foo', 'foo'], [Sut::isType('string'), Sut::isType('string')], true],
            [['foo', 'foo'], [Sut::isType('string'), 'baz'], false],
            [['foo', 'foo'], ['baz', Sut::isType('string')], false],
            [['foo', 'foo'], [Sut::isType('string'), Sut::isType('array')], false],
            [[new \stdClass(), 'foo'], [Sut::isInstanceOf('\stdClass'), Sut::isType('string')], true],
            [[new \stdClass(), 'foo'], [Sut::isInstanceOf('\stdClass'), 'foo'], true]
        ];
    }

    /**
     * @test
     * it should allow verifying multiple arguments
     * @dataProvider multipleCallArgsAndConstraints
     */
    public function it_should_allow_verifying_multiple_arguments($callArgs, $expectedArgs, $shouldPass)
    {
        if (!$shouldPass) {
            $this->expectFailure();
        }
        $func = Sut::replace(__NAMESPACE__ . '\beta');

        call_user_func_array(__NAMESPACE__ . '\beta', $callArgs);

        $func->wasCalledWithOnce($expectedArgs);
    }
}

function alpha($arg)
{

}

function beta($arg1, $arg2)
{

}


