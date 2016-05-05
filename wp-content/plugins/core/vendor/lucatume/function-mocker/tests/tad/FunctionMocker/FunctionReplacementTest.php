<?php

	namespace tad\FunctionMocker\Tests;

	use tad\FunctionMocker\FunctionMocker;
	use tad\FunctionMocker\MockCallLogger;
	use tad\FunctionMocker\TestCase;

	class FunctionReplacementTest extends \PHPUnit_Framework_TestCase
	{

		public function setUp() {
			FunctionMocker::setUp();
		}

		public function tearDown() {
			FunctionMocker::tearDown();
		}

		/**
		 * @test
		 * it should return a VerifierInterface object when replacing a function
		 */
		public function it_should_return_null_when_stubbin_a_function() {
			$ret = FunctionMocker::replace( __NAMESPACE__ . '\someFunction' );

			$this->assertInstanceOf( 'tad\FunctionMocker\Call\Verifier\VerifierInterface', $ret );
		}

		/**
		 * @test
		 * it should return the set return value when replacing a function and setting a return value
		 */
		public function it_should_return_the_set_return_value_when_replacing_a_function_and_setting_a_return_value() {
			$ret = FunctionMocker::replace( __NAMESPACE__ . '\someFunction', 23 );

			$this->assertEquals( 23, someFunction() );
		}

		/**
		 * @test
		 * it should return the callback return value when replacing a function and setting a callback return value
		 */
		public function it_should_return_the_callback_return_value_when_replacing_a_function_and_setting_a_callback_return_value() {
			$ret = FunctionMocker::replace( __NAMESPACE__ . '\someFunction', function ( $value ) {
				return $value + 1;
			} );

			$this->assertEquals( 24, someFunction( 23 ) );

			$ret = FunctionMocker::replace( __NAMESPACE__ . '\someFunction', function ( $a, $b ) {
				return $a + $b;
			} );

			$this->assertEquals( 23, someFunction( 11, 12 ) );
		}

		/**
		 * @test
		 * it should return null when replacing a function and not setting a return value
		 */
		public function it_should_return_null_when_replacing_a_function_and_not_setting_a_return_value() {
			$ret = FunctionMocker::replace( __NAMESPACE__ . '\someFunction' );

			$this->assertNull( someFunction() );
			$ret->wasCalledOnce();
			$ret->wasCalledTimes( 1 );
		}

		/**
		 * @test
		 * it should allow setting various return values when spying a function
		 */
		public function it_should_allow_setting_various_return_values_when_spying_a_function() {
			$ret = FunctionMocker::replace( __NAMESPACE__ . '\someFunction', 23 );

			$this->assertEquals( 23, someFunction() );

			$ret = FunctionMocker::replace( __NAMESPACE__ . '\someFunction', function ( $value ) {
				return $value + 1;
			} );

			$this->assertEquals( 24, someFunction( 23 ) );

			$ret = FunctionMocker::replace( __NAMESPACE__ . '\someFunction', function ( $a, $b ) {
				return $a + $b;
			} );

			$this->assertEquals( 23, someFunction( 11, 12 ) );
		}

		/**
		 * @test
		 * it should allow verifying calls on spied function
		 */
		public function it_should_allow_verifying_calls_on_spied_function() {
			$spy = FunctionMocker::replace( __NAMESPACE__ . '\someFunction' );

			someFunction( 12 );
			someFunction( 11 );

			$spy->wasCalledTimes( 2 );
			$spy->wasCalledWithTimes( array( 12 ), 1 );
			$spy->wasCalledWithTimes( array( 11 ), 1 );
			$spy->wasNotCalledWith( array( 10 ) );

			$this->setExpectedException( '\PHPUnit_Framework_AssertionFailedError' );
			$spy->wasCalledTimes( 0 );
		}

	}



