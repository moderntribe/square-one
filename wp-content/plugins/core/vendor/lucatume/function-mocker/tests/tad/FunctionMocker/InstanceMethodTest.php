<?php

    namespace tests\tad\FunctionMocker;


    use tad\FunctionMocker\FunctionMocker;
    use tad\FunctionMocker\Tests\TestCase;

    class InstanceMethodTest extends TestCase
    {

        /**
         * @var string
         */
        protected $testClass;

        public function setUp()
        {
            $this->testClass = __NAMESPACE__ . '\TestClass';
            FunctionMocker::setUp();
        }

        public function tearDown()
        {
            FunctionMocker::tearDown();
        }

        /**
         * @test
         * it should return an object extending the replaced one
         */
        public function it_should_return_an_object_extending_the_replaced_one()
        {
            $stub = FunctionMocker::replace($this->testClass . '::methodOne');

            $this->assertInstanceOf($this->testClass, $stub);
        }

        /**
         * @test
         * it should return an object implementing the PHPUnit_Framework_MockObject_MockObject interface when stubbing
         */
        public function it_should_return_an_object_implementing_the_php_unit_framework_mock_object_mock_object_interface_when_stubbing()
        {
            $stub = FunctionMocker::replace($this->testClass . '::methodOne');

            $this->assertInstanceOf('\PHPUnit_Framework_MockObject_MockObject', $stub);
        }

        /**
         * @test
         * it should return an object implementing the VerifierInterface interface when replacing
         */
        public function it_should_return_an_object_implementing_the_verifier_interface_when_replacing()
        {
            $replacement = FunctionMocker::replace($this->testClass . '::methodOne');

            $this->assertInstanceOf('tad\FunctionMocker\Call\Verifier\VerifierInterface', $replacement);
        }

        /**
         * @test
         * it should return null when replacing an instance method and not setting a return value
         */
        public function it_should_return_null_when_replacing_an_instance_method_and_not_setting_a_return_value()
        {
            $replacement = FunctionMocker::replace($this->testClass . '::methodOne');

            $this->assertNull($replacement->methodOne());
        }

        public function returnValues()
        {
            return array(
                array(23),
                array('foo'),
                array(array()),
                array(
                    array(
                        1,
                        2,
                        3
                    )
                ),
                array(
                    array(
                        'one'   => 1,
                        'two'   => 2,
                        'three' => 3
                    )
                ),
                array(new \stdClass()),
                array(null)
            );
        }

        /**
         * @test
         * it should return a set return value when replacing and setting a return value
         * @dataProvider returnValues
         */
        public function it_should_return_a_set_return_value_when_replacing_and_setting_a_return_value($returnValue)
        {
            $replacement = FunctionMocker::replace($this->testClass . '::methodOne', $returnValue);

            $this->assertEquals($returnValue, $replacement->methodOne());
        }

        /**
         * @test
         * it should return the return value of a callback function when replacing and setting the return value to a
         * callback function
         */
        public function it_should_return_the_return_value_of_a_callback_function_when_replacing_and_setting_the_return_value_to_a_callback_function()
        {
            $returnValue = function () {
                return 'some';
            };
            $replacement = FunctionMocker::replace($this->testClass . '::methodOne', $returnValue);

            $this->assertEquals('some', $replacement->methodOne());
        }

        /**
         * @test
         * it should return an object implementing the VerifierInterface interface when spying
         */
        public function it_should_return_an_object_implementing_the_verifier_interface_when_spying()
        {
            $spy = FunctionMocker::replace($this->testClass . '::methodOne');

            $this->assertInstanceOf('\tad\FunctionMocker\Call\Verifier\VerifierInterface', $spy);
        }

        /**
         * @test
         * it should return null if not setting a return value when spying an instance method
         */
        public function it_should_return_null_if_not_setting_a_return_value_when_spying_an_instance_method()
        {
            $spy = FunctionMocker::replace($this->testClass . '::methodOne');

            $this->assertNull($spy->methodOne());
        }

        /**
         * @test
         * it should return a set return value when spying and setting a return value
         * @dataProvider returnValues
         */
        public function it_should_return_a_set_return_value_when_spying_and_setting_a_return_value($returnValue)
        {
            $spy = FunctionMocker::replace($this->testClass . '::methodOne', $returnValue);

            $this->assertEquals($returnValue, $spy->methodOne());
        }

        /**
         * @test
         * it should return the return value of a callback function when spying and setting a callback function as
         * return value
         */
        public function it_should_return_the_return_value_of_a_callback_function_when_spying_and_setting_a_callback_function_as_return_value()
        {
            $returnValue = function () {
                return 'some';
            };
            $spy = FunctionMocker::replace($this->testClass . '::methodOne', $returnValue);

            $this->assertEquals('some', $spy->methodOne());
        }

        public function timesCallsAndThrows()
        {
            return array(
                array(
                    2,
                    2,
                    false
                ),
                array(
                    2,
                    1,
                    true
                ),
                array(
                    2,
                    3,
                    true
                ),
                array(
                    '2',
                    2,
                    false
                ),
                array(
                    '>=2',
                    2,
                    false
                ),
                array(
                    '>=2',
                    3,
                    false
                ),
                array(
                    '>=2',
                    1,
                    true
                ),
                array(
                    '<=2',
                    2,
                    false
                ),
                array(
                    '<=2',
                    3,
                    true
                ),
                array(
                    '<=2',
                    1,
                    false
                ),
                array(
                    '<2',
                    2,
                    true
                ),
                array(
                    '<2',
                    1,
                    false
                ),
                array(
                    '<2',
                    4,
                    true
                ),
                array(
                    '>2',
                    2,
                    true
                ),
                array(
                    '>2',
                    1,
                    true
                ),
                array(
                    '>2',
                    3,
                    false
                ),
                array(
                    '!=2',
                    2,
                    true
                ),
                array(
                    '!=2',
                    1,
                    false
                ),
                array(
                    '!=2',
                    3,
                    false
                ),
                array(
                    '==2',
                    3,
                    true
                ),
                array(
                    '==2',
                    2,
                    false
                ),
                array(
                    '==2',
                    1,
                    true
                )
            );
        }

        /**
         * @test
         * it should allow verifying an instance method was called times when spying
         * @dataProvider timesCallsAndThrows
         */
        public function it_should_allow_verifying_an_instance_method_was_called_times_when_spying($times, $calls, $shouldThrow)
        {
            if ($shouldThrow) {
                $this->expectFailure();
            }
            $spy = FunctionMocker::replace($this->testClass . '::methodOne');

            for ($i = 0; $i < $calls; $i++) {
                $spy->methodOne();
            }

            $spy->wasCalledTimes($times, 'methodOne');
        }

        /**
         * @test
         * it should allow verifying an instance method was called times with args when spying
         * @dataProvider timesCallsAndThrows
         */
        public function it_should_allow_verifying_an_instance_method_was_called_times_with_args_when_spying($times, $calls, $shouldThrow)
        {
            if ($shouldThrow) {
                $this->expectFailure();
            }
            $spy = FunctionMocker::replace($this->testClass . '::methodTwo');

            for ($i = 0; $i < $calls; $i++) {
                $spy->methodTwo(23, 23);
            }

            $spy->wasCalledWithTimes(array(
                23,
                23
            ), $times, 'methodTwo');
        }

        /**
         * @test
         * it should allow calling the replacement inside a closure
         */
        public function it_should_allow_calling_the_replacement_inside_a_closure()
        {
            $methodOne = FunctionMocker::replace($this->testClass . '::methodOne');

            $caller = function (TestClass $testClass) {
                $testClass->methodOne();
            };

            $caller($methodOne);

            $methodOne->wasCalledTimes(1, 'methodOne');
        }

        /**
         * @test
         * it should allow replacing a concrete class instance method with a callback and pass arguments to it
         */
        public function it_should_allow_replacing_a_concrete_class_instance_method_with_a_callback_and_pass_arguments_to_it()
        {
            $testClass = FunctionMocker::replace($this->testClass . '::methodTwo', function ($one, $two) {
                return $one + $two;
            });

            $this->assertEquals(4, $testClass->methodTwo(1, 3));
        }

        public function callsAndConstraints()
        {
            return [
                [
                    [new \stdClass(), [1, 2, 3], 'foo'],
                    [
                        FunctionMocker::isInstanceOf('stdClass'),
                        FunctionMocker::isType('array'),
                        FunctionMocker::isType('string')
                    ],
                    false
                ],
                [
                    [null, [1, 2, 3], 'foo'],
                    [
                        FunctionMocker::isInstanceOf('stdClass'),
                        FunctionMocker::isType('array'),
                        FunctionMocker::isType('string')
                    ],
                    true
                ],
                [
                    [new \stdClass(), null, 'foo'],
                    [
                        FunctionMocker::isInstanceOf('stdClass'),
                        FunctionMocker::isType('array'),
                        FunctionMocker::isType('string')
                    ],
                    true
                ],
                [
                    [new \stdClass(), [1, 2, 3], null],
                    [
                        FunctionMocker::isInstanceOf('stdClass'),
                        FunctionMocker::isType('array'),
                        FunctionMocker::isType('string')
                    ],
                    true
                ],
                [
                    ['foo', [], null],
                    [
                        FunctionMocker::isInstanceOf('stdClass'),
                        FunctionMocker::isType('array'),
                        FunctionMocker::isType('string')
                    ],
                    true
                ],
                [
                    ['foo', [], null],
                    [
                        FunctionMocker::anything(),
                        FunctionMocker::anything(),
                        FunctionMocker::anything()
                    ],
                    false
                ]
            ];
        }

        /**
         * @test
         * it should allow verifying method calls using PHPUnit constraints
         * @dataProvider callsAndConstraints
         */
        public function it_should_allow_verifying_method_calls_using_php_unit_constraints($in, $exp, $shouldThrow)
        {
            if ($shouldThrow) {
                $this->expectFailure();
            }

            $sut = FunctionMocker::replace($this->testClass . '::methodThree');

            call_user_func_array([$sut, 'methodThree'], $in);

            $sut->wasCalledWithOnce($exp, 'methodThree');
        }

        /**
         * @test
         * it should allow start an instance method replacement chain trying to replace a class name only
         */
        public function it_should_allow_start_an_instance_method_replacement_chain_trying_to_replace_a_class_name_only()
        {
            $sut = FunctionMocker::replace($this->testClass);

            $this->assertInstanceOf('tad\FunctionMocker\Forge\Step', $sut);
        }
    }


    class TestClass
    {

        public function methodOne()
        {

        }

        public function methodTwo($one, $two)
        {

        }

        public function methodThree($object, $array, $string)
        {

        }
    }

