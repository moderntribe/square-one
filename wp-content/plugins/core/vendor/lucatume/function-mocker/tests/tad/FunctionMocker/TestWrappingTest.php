<?php
/**
 * Created by PhpStorm.
 * User: Luca
 * Date: 20/01/15
 * Time: 14:47
 */

namespace tad\FunctionMocker;


class TestWrappingTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @test
     * it should allow wrapping the test case and call its methods
     */
    public function it_should_allow_wrapping_the_test_case_and_call_its_methods()
    {
        FunctionMocker::setTestCase($this);

        $this->assertEquals(23 ,FunctionMocker::someMethod());
    }

    public function someMethod(){
        return 23;
    }
}
