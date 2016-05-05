<?php
namespace tests\tad\FunctionMocker;


use tad\FunctionMocker\FunctionMocker;

class AliasedClassesTest extends \PHPUnit_Framework_TestCase
{

	public function setUp() {
		FunctionMocker::setUp();
	}

	public function tearDown() {
		FunctionMocker::tearDown();
	}

	/**
	 * @test
	 * it should allow replacing a class extending an aliased class
	 */
	public function it_should_allow_replacing_a_class_extending_an_aliased_class() {
		$class = 'Another\Name\Space\Class2';

		// should not throw
		FunctionMocker::replace( $class );
	}

	/**
	 * @test
	 * it should allow replacing a class method having an aliased type hinted parameter
	 */
	public function it_should_allow_replacing_a_class_method_having_an_aliased_type_hinted_parameter() {
		$class = 'Another\Acme\Class3';

		FunctionMocker::replace( $class )->method( 'testMethod' )->get();
	}
}
