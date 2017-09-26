<?php

namespace tests\tad\FunctionMocker;

use tad\FunctionMocker\FunctionMocker as Test;

interface SomeI
{

	public function methodOne();

	public function methodWithArgs($string, $int);
}

class InstanceMockingTest extends \PHPUnit\Framework\TestCase
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
	 * it should allow mocking an interface instance method
	 */
	public function it_should_allow_mocking_an_interface_instance_method() {
		$mock = Test::replace( __NAMESPACE__ . '\SomeI::methodOne', 23 );

		$this->assertEquals( 23, $mock->methodOne() );
	}

	/**
	 * @test
	 * it should allow mocking an interface instance method and return a callback
	 */
	public function it_should_allow_mocking_an_interface_instance_method_and_return_a_callback() {
		$mock = Test::replace( __NAMESPACE__ . '\SomeI::methodOne', function () {
			return 23;
		} );

		$this->assertEquals( 23, $mock->methodOne() );
	}

	/**
	 * @test
	 * it should allow mocking an interface instance method and pass arguments to it
	 */
	public function it_should_allow_mocking_an_interface_instance_method_and_pass_arguments_to_it() {
		$mock = Test::replace( __NAMESPACE__ . '\SomeI::methodWithArgs', function ( $string, $int ) {
			return 23 + strlen( $string ) + $int;
		} );

		$this->assertEquals( 28, $mock->methodWithArgs( 'foo', 2 ) );
	}

	/**
	 * @test
	 * it should allow mocking an interface using the chain
	 */
	public function it_should_allow_mocking_an_interface_using_the_chain() {
		$mock = Test::replace( __NAMESPACE__ . '\SomeI' )->get();

		$this->assertInstanceOf( __NAMESPACE__ . '\SomeI', $mock );
	}

	/**
	 * @test
	 * it should return a mock step object when mocking using the chain
	 */
	public function it_should_return_a_mock() {
		$step = Test::replace( __NAMESPACE__ . '\SomeI' );

		$class = 'tad\FunctionMocker\Forge\Step';
		$this->assertInstanceOf( $class, $step );
	}

	/**
	 * @test
	 * it should allow mocking an interface method using the chain
	 */
	public function it_should_allow_mocking_an_interface_method_using_the_chain() {
		$mock = Test::replace( __NAMESPACE__ . '\SomeI' )->method( 'methodOne', 'foo' )->get();

		$this->assertEquals( 'foo', $mock->methodOne() );
	}

	/**
	 * @test
	 * it should allow mocking an interface method and make it return null using the chain
	 */
	public function it_should_allow_mocking_an_interface_method_and_make_it_return_null_using_the_chain() {
		$mock = Test::replace( __NAMESPACE__ . '\SomeI' )->method( 'methodOne' )->get();

		$this->assertNull( $mock->methodOne() );
	}
}
