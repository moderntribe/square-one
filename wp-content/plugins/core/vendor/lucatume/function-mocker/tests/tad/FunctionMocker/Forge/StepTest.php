<?php
    namespace tests\tad\FunctionMocker\Forge;

    use tad\FunctionMocker\Forge\Step;
    use tad\FunctionMocker\Replacers\InstanceForger;

    class StepTest extends \PHPUnit_Framework_TestCase
    {
        protected $class;

        public function setUp()
        {
            $this->class = $class = __NAMESPACE__ . '\StepDummyClass';
        }

        /**
         * @test
         * it should throw if passing a non string arg
         */
        public function it_should_throw_if_passing_a_non_string_arg()
        {
            $this->setExpectedException('\Exception');
            Step::instance(23);
        }

        /**
         * @test
         * it should throw if the class name is a non existing class
         */
        public function it_should_throw_if_the_class_name_is_a_non_existing_class()
        {
            $this->setExpectedException('\Exception');
            Step::instance('SomeUnrealClass');
        }

        /**
         * @test
         * it should return a wrapped mock
         */
        public function it_should_return_a_wrapped_mock()
        {
            $sut = new Step();
            $sut->setClass($this->class);
            $this->set_instance_forger_on($sut);

            $mock = $sut->get();

            $this->assertInstanceOf('tad\FunctionMocker\Call\Verifier\VerifierInterface', $mock);
        }

        /**
         * @param $sut
         */
        private function set_instance_forger_on($sut)
        {
            $forger = new InstanceForger();
            $forger->setTestCase($this);
            $sut->setInstanceForger($forger);
        }

        /**
         * @test
         * it should allow defining methods to be replaced
         */
        public function it_should_allow_defining_methods_to_be_replaced()
        {
            $sut = new Step($this->class);
            $sut->setClass($this->class);
            $this->set_instance_forger_on($sut);

            $sut->method('methodOne');
            $mock = $sut->get();

            $this->assertTrue(method_exists($mock, 'methodOne'));
        }

        /**
         * @test
         * it should be able to mock more than one method
         */
        public function it_should_be_able_to_mock_more_than_one_method()
        {
            $sut = new Step($this->class);
            $sut->setClass($this->class);
            $this->set_instance_forger_on($sut);

            $sut->method('methodOne');
            $sut->method('methodTwo');
            $mock = $sut->get();

            $this->assertTrue(method_exists($mock, 'methodOne'));
            $this->assertTrue(method_exists($mock, 'methodTwo'));
        }

        public function primitiveReturnValues()
        {
            $_values = [
                23,
                'foo',
                new \stdClass(),
                array(),
                array('foo', 'baz'),
                array('some' => 'value', 'foo' => 23)
            ];

            return array_map(function ($val) {
                return [$val];
            }, $_values);
        }

        /**
         * @test
         * it should be able to replace methods and set primitive return value
         * @dataProvider primitiveReturnValues
         */
        public function it_should_be_able_to_replace_methods_and_set_primitive_return_value($exp)
        {
            $sut = new Step($this->class);
            $sut->setClass($this->class);
            $this->set_instance_forger_on($sut);

            $sut->method('methodOne', $exp);
            $mock = $sut->get();

            $this->assertEquals($exp, $mock->methodOne());
        }

        /**
         * @test
         * it should allow setting the return values of replaced methods to callback functions
         */
        public function it_should_allow_setting_the_return_values_of_replaced_methods_to_callback_functions()
        {
            $sut = new Step($this->class);
            $sut->setClass($this->class);
            $this->set_instance_forger_on($sut);
            $sut->method('methodOne', function () {
                return 23;
            });
            $mock = $sut->get();

            $this->assertEquals(23, $mock->methodOne());
        }

        /**
         * @test
         * it should allow setting return values of replaced methods to callback functions and pass them same arguments as original methods
         */
        public function it_should_allow_setting_return_values_of_replaced_methods_to_callback_functions_and_pass_them_same_arguments_as_original_methods()
        {
            $sut = new Step($this->class);
            $sut->setClass($this->class);
            $this->set_instance_forger_on($sut);
            $sut->method('methodThree', function ($one, $two) {
                return $one + $two;
            });
            $mock = $sut->get();

            $this->assertEquals(23, $mock->methodThree(1, 22));
        }

        /**
         * @test
         * it should allow replacing self returning methods
         */
        public function it_should_allow_replacing_self_returning_methods()
        {
            $sut = new Step($this->class);
            $sut->setClass($this->class);
            $this->set_instance_forger_on($sut);

            $mock = $sut->method('methodOne', '->')
                ->get();

            $this->assertInstanceOf($this->class, $mock->methodOne());
        }

        /**
         * @test
         * it should allow replace chain and value returning methods
         */
        public function it_should_allow_replace_chain_and_value_returning_methods()
        {
            $sut = new Step($this->class);
            $sut->setClass($this->class);
            $this->set_instance_forger_on($sut);

            $mock = $sut->method('methodOne', '->')
                ->method('methodTwo', 23)
                ->get();

            $this->assertEquals(23, $mock->methodOne()->methodTwo());
        }

        /**
         * @test
         * it should allow verifying call times on methods
         */
        public function it_should_allow_verifying_call_times_on_methods()
        {
            $sut = new Step($this->class);
            $sut->setClass($this->class);
            $this->set_instance_forger_on($sut);

            $mock = $sut->method('methodOne', '->')
                ->method('methodTwo', 23)
                ->get();

            $mock->methodOne();
            $mock->methodTwo();

            $mock->wasCalledOnce('methodOne');
            $mock->wasCalledOnce('methodTwo');
            $mock->wasCalledWithTimes(['foo'], 0, 'methodOne');
        }

        /**
         * @test
         * it should create a Verifier object when calling the method verify
         */
        public function it_should_create_a_verifier_object_when_calling_the_method_verify()
        {
            $sut = new Step($this->class);
            $sut->setClass($this->class);
            $this->set_instance_forger_on($sut);

            $verifier = $sut->verify();

            $this->assertInstanceOf('tad\FunctionMocker\Call\Verifier\VerifierInterface', $verifier);
        }

        /**
         * @test
         * it should share the same original mock object
         */
        public function it_should_share_the_same_original_mock_object()
        {
            $sut = new Step($this->class);
            $sut->setClass($this->class);
            $this->set_instance_forger_on($sut);

            $mock = $sut->get();
            $verifier = $sut->verify();

            $this->assertSame($mock->__functionMocker_originalMockObject, $verifier->__functionMocker_originalMockObject);
        }

        /**
         * @test
         * it should share the same invoked recorder
         */
        public function it_should_share_the_same_invoked_recorder()
        {
            $sut = new Step($this->class);
            $sut->setClass($this->class);
            $this->set_instance_forger_on($sut);

            $mock = $sut->get();
            $verifier = $sut->verify();

            $this->assertSame($mock->__functionMocker_invokedRecorder, $verifier->__functionMocker_invokedRecorder);
        }

        /**
         * @test
         * it should allow skipping method name definition when using verify
         */
        public function it_should_allow_skipping_method_name_definition_when_using_verify()
        {
            $sut = new Step($this->class);
            $sut->setClass($this->class);
            $this->set_instance_forger_on($sut);

            $mock = $sut->method('methodOne')->get();
            $mock->methodOne();

            $verifier = $sut->verify();
            $verifier->methodOne()->wasCalledOnce();
        }

        /**
         * @test
         * it should allow verifying method was not called
         */
        public function it_should_allow_verifying_method_was_not_called()
        {
            $sut = new Step($this->class);
            $sut->setClass($this->class);
            $this->set_instance_forger_on($sut);

            $mock = $sut->method('methodOne')->get();

            $verifier = $sut->verify();
            $verifier->methodOne()->wasNotCalled();
        }

        /**
         * @test
         * it should allow verifying method was called times
         */
        public function it_should_allow_verifying_method_was_called_times()
        {
            $sut = new Step($this->class);
            $sut->setClass($this->class);
            $this->set_instance_forger_on($sut);

            $mock = $sut->method('methodOne')->get();
            $mock->methodOne();
            $mock->methodOne();
            $mock->methodOne();

            $verifier = $sut->verify();
            $verifier->methodOne()->wasCalledTimes(3);
        }

        /**
         * @test
         * it should allow verifying method was called with
         */
        public function it_should_allow_verifying_method_was_called_with()
        {
            $sut = new Step($this->class);
            $sut->setClass($this->class);
            $this->set_instance_forger_on($sut);

            $mock = $sut->method('methodThree')->get();
            $mock->methodThree('foo', 'bar');

            $verifier = $sut->verify();
            $verifier->methodThree('foo', 'bar')->wasCalledOnce();
        }

        /**
         * @test
         * it should allow verifying method was called times with
         */
        public function it_should_allow_verifying_method_was_called_times_with()
        {
            $sut = new Step($this->class);
            $sut->setClass($this->class);
            $this->set_instance_forger_on($sut);

            $mock = $sut->method('methodThree')->get();
            $mock->methodThree('foo', 'bar');
            $mock->methodThree('foo', 'bar');
            $mock->methodThree('foo', 'bar');

            $verifier = $sut->verify();
            $verifier->methodThree('foo', 'bar')->wasCalledTimes(3);
        }

        /**
         * @test
         * it should return the same verifier instance on each call
         */
        public function it_should_return_the_same_verifier_instance_on_each_call()
        {
            $sut = new Step($this->class);
            $sut->setClass($this->class);
            $this->set_instance_forger_on($sut);

            $this->assertSame($sut->verify(), $sut->verify());
        }
    }


    class StepDummyClass
    {
        public function methodOne()
        {

        }

        public function methodTwo()
        {

        }

        public function methodThree($one, $two)
        {

        }
    }
