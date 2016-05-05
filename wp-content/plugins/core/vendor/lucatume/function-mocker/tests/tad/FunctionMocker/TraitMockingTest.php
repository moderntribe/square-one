<?php

	namespace tests\tad\FunctionMocker;


	use tad\FunctionMocker\FunctionMocker as Test;

	trait TestTrait
	{

		public static function methodThree()
		{

		}

		public static function methodFour($one, $two)
		{

		}

		public function methodOne()
		{

		}

		public function methodTwo($one, $two)
		{

		}
	}

	class TraitMockingTest extends \PHPUnit_Framework_TestCase
	{

		/**
		 * @var string
		 */
		protected $sutClass;

		public function setUp() {
			$this->sutClass = __NAMESPACE__ . '\TestTrait';
			Test::setUp();
		}

		public function tearDown() {
			Test::tearDown();
		}

		public function returnValues() {
			return [
				[ 23, 23 ], [
					function () {
						return 23;
					}, 23
				]
			];

		}

		/**
		 * @test
		 * it should replacing a trait instance method
		 * @dataProvider returnValues
		 */
		public function it_should_replacing_a_trait_instance_method( $in, $out ) {
			$mock = Test::replace( $this->sutClass . '::methodOne', $in );

			$this->assertEquals( $out, $mock->methodOne() );
		}

		/**
		 * @test
		 * it should allow replacing a trait instance method with a callback and pass arguments to it
		 */
		public function it_should_allow_replacing_a_trait_instance_method_with_a_callback_and_pass_arguments_to_it() {
			$mock = Test::replace( $this->sutClass . '::methodTwo', function ( $one, $two ) {
				return $one + $two;
			} );

			$this->assertEquals( 23, $mock->methodTwo( 11, 12 ) );
		}

	}