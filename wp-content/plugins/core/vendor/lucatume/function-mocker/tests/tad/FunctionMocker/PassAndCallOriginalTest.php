<?php
	/**
	 * Created by PhpStorm.
	 * User: Luca
	 * Date: 15/12/14
	 * Time: 09:48
	 */

	namespace tests\tad\FunctionMocker;


	use tad\FunctionMocker\FunctionMocker;

	class PassAndCallOriginalTest extends \PHPUnit_Framework_TestCase
	{

		public function setUp() {
			FunctionMocker::setUp();
		}

		public function tearDown() {
			FunctionMocker::tearDown();
		}

		/**
		 * @test
		 * it should allow calling original function and have the original return value
		 */
		public function it_should_allow_calling_original_function_and_have_the_original_return_value() {
			FunctionMocker::replace( __NAMESPACE__ . '\passingFunction', function () {
				return FunctionMocker::callOriginal();
			} );

			$this->assertEquals( 'foo', passingFunction() );
		}

		/**
		 * @test
		 * it should allow calling the original function with args
		 */
		public function it_should_allow_calling_the_original_function_with_args() {
			FunctionMocker::replace( __NAMESPACE__ . '\passingFunctionWithArgs', function ( $one, $two ) {
				return FunctionMocker::callOriginal( [ $one, $two ] );
			} );

			$this->assertEquals( 'foo and baz', passingFunctionWithArgs( 'foo', 'baz' ) );
		}

		/**
		 * @test
		 * it should allow calling original method and have the original return value
		 */
		public function it_should_allow_calling_original_method_and_have_the_original_return_value() {
			FunctionMocker::replace( __NAMESPACE__ . '\PassingClass::passingStaticMethod', function () {
				return FunctionMocker::callOriginal();
			} );

			$this->assertEquals( 'foo', PassingClass::passingStaticMethod() );
		}

		/**
		 * @test
		 * it should allow calling the original method with args
		 */
		public function it_should_allow_calling_the_original_method_with_args() {
			FunctionMocker::replace( __NAMESPACE__ . '\PassingClass::passingStaticMethodWithArgs', function ( $one, $two ) {
				return FunctionMocker::callOriginal( [ $one, $two ] );
			} );

			$this->assertEquals( 'foo and baz', PassingClass::passingStaticMethodWithArgs( 'foo', 'baz' ) );
		}

		/**
		 * @test
		 * it should allow setting up conditional calls to the original function
		 */
		public function it_should_allow_setting_up_conditional_calls_to_the_original_function() {
			FunctionMocker::replace( __NAMESPACE__ . '\passingFunctionWithArgs', function ( $one, $two ) {
				if ( $one === 'some' ) {
					return 'test';
				}

				return FunctionMocker::callOriginal( [ $one, $two ] );
			} );

			$this->assertEquals( 'foo and baz', passingFunctionWithArgs( 'foo', 'baz' ) );
			$this->assertEquals( 'test', passingFunctionWithArgs( 'some', 'baz' ) );
		}

		/**
		 * @test
		 * it should allow setting up conditional calls to the original static method
		 */
		public function it_should_allow_setting_up_conditional_calls_to_the_original_static_method() {
			FunctionMocker::replace( __NAMESPACE__ . '\PassingClass::passingStaticMethodWithArgs', function ( $one, $two ) {
				if ( $one === 'some' ) {
					return 'test';
				}

				return FunctionMocker::callOriginal( [ $one, $two ] );
			} );

			$this->assertEquals( 'foo and baz', PassingClass::passingStaticMethodWithArgs( 'foo', 'baz' ) );
			$this->assertEquals( 'test', PassingClass::passingStaticMethodWithArgs( 'some', 'baz' ) );
		}
	}


	function passingFunction() {
		return 'foo';
	}

	function passingFunctionWithArgs( $one, $two ) {
		return $one . ' and ' . $two;
	}


	class PassingClass {

		public static function passingStaticMethod() {
			return 'foo';
		}

		public static function passingStaticMethodWithArgs( $one, $two ) {
			return $one . ' and ' . $two;
		}
	}
