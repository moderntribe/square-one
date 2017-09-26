<?php

	namespace tests\tad\FunctionMocker;

	use tad\FunctionMocker\FunctionMocker as Test;

	class AbstractClassMockingTest extends \PHPUnit\Framework\TestCase
	{

		protected $ns;

		public function setUp() {
			Test::setUp();

			$this->ns = __NAMESPACE__;
		}

		public function tearDown() {
			Test::tearDown();
		}

		/**
		 * @test
		 * it should allow mocking an abstract class concrete instance method
		 */
		public function it_should_allow_mocking_an_abstract_class_concrete_instance_method() {
			$mock = Test::replace( $this->ns . '\SomeClass->methodOne', 23 );

			$this->assertEquals( 23, $mock->methodOne() );
		}

		/**
		 * @test
		 * it should allow mocking an abstract class abstract instance method
		 */
		public function it_should_allow_mocking_an_abstract_class_abstract_instance_method() {
			$mock = Test::replace( $this->ns . '\SomeClass::methodTwo', 23 );

			$this->assertEquals( 23, $mock->methodTwo() );
		}

		/**
		 * @test
		 * it should allow mocking an abstract class instance method and set a callback return value
		 */
		public function it_should_allow_mocking_an_abstract_class_instance_method_and_set_a_callback_return_value() {
			$mock = Test::replace( $this->ns . '\SomeClass::methodTwo', function () {
				return 23;
			} );

			$this->assertEquals( 23, $mock->methodTwo() );
		}

		/**
		 * @test
		 * it should allow replacing an abstract class instance method with a callback and pass arguments to it
		 */
		public function it_should_allow_replacing_an_abstract_class_instance_method_with_a_callback_and_pass_arguments_to_it() {
			$mock = Test::replace( $this->ns . '\SomeClass::methodThree', function ( $string, $int ) {
				return 23 + strlen( $string ) + $int;
			} );

			$this->assertEquals( 28, $mock->methodThree( 'foo', 2 ) );
		}

		/**
		 * @test
		 * it should allow replacing an abstract class abstract instance method with a callback and pass arguments to it
		 */
		public function it_should_allow_replacing_an_abstract_class_abstract_instance_method_with_a_callback_and_pass_arguments_to_it() {
			$mock = Test::replace( $this->ns . '\SomeClass::methodFour', function ( $string, $int ) {
				return 23 + strlen( $string ) + $int;
			} );

			$this->assertEquals( 28, $mock->methodFour( 'foo', 2 ) );
		}
	}


	abstract class SomeClass {

		public function methodOne() {
		}

		abstract public function methodTwo();

		public function methodThree( $one, $two ) {
		}

		abstract public function methodFour( $one, $two );
	}
