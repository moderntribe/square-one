<?php

namespace tests\tad\FunctionMocker;


use tad\FunctionMocker\FunctionMocker as Test;

class AssertionWrappingTest extends \PHPUnit\Framework\TestCase
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
     * it should allow wrapping the assertions with no setup
     */
    public function it_should_allow_wrapping_the_assertions_with_no_setup()
    {
        Test::assertTrue(true);
    }

    /**
     * @test
     * it should allow setting the test case to be used and forward calls to it
     */
    public function it_should_allow_setting_the_test_case_to_be_used_and_forward_calls_to_it()
    {
        $testCase = new MockTestCase();
        Test::setTestCase($testCase);

        $this->assertEquals(23, Test::utilityMethod(11,12));
    }

    public function aUtilityMethod($one, $two){
        return $one + $two;
    }

    /**
     * @test
     * it should allow wrapping own test case utility method
     */
    public function it_should_allow_wrapping_own_test_case_utility_method()
    {
        Test::setTestCase($this);

        $this->assertEquals(23, Test::aUtilityMethod(11,12));
    }
}

class MockTestCase extends \PHPUnit\Framework\TestCase {
    public function utilityMethod($one, $two){
        return $one + $two;
    }
}
