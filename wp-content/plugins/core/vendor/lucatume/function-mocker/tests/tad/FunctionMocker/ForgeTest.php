<?php

namespace tests\tad\FunctionMocker {


    use tad\FunctionMocker\FunctionMocker as Sut;

    class ForgeTest extends \PHPUnit_Framework_TestCase
    {
        protected $class;

        public function setUp()
        {
            Sut::setUp();
            $this->class = __NAMESPACE__ . '\ForgeClass';
        }

        public function tearDown()
        {
            Sut::tearDown();
        }

        /**
         * @test
         * it should allow calling the getMock method on a namespaced
         */
        public function it_should_allow_calling_the_forge_method_on_a_namespaced_class()
        {
            Sut::forge($this->class);
        }

        /**
         * @test
         * it should allow calling the getMock method on a global class
         */
        public function it_should_allow_calling_the_forge_method_on_a_global_class()
        {
            Sut::forge('GlobalClass');
        }


        /**
         * @test
         * it should return ForgeStepInterface object
         */
        public function it_should_return_forge_step_interface_object()
        {
            $this->assertInstanceOf('tad\FunctionMocker\Forge\StepInterface', Sut::forge('GlobalClass'));
        }
    }

    class ForgeClass
    {

    }
}

namespace {
    class GlobalClass
    {

    }
}
