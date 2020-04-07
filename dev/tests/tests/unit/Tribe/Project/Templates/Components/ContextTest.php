<?php namespace Tribe\Project\Templates\Components;

use tad\FunctionMocker\FunctionMocker;
use Tribe\Project\Templates\Component_Factory;
use Twig\Environment;

class ContextTest extends \Codeception\Test\Unit {
	/**
	 * @var \UnitTester
	 */
	protected $tester;

	protected function _setUpBeforeClass(): void {
		FunctionMocker::setUp();
		FunctionMocker::replace( 'esc_attr', function ( $string ) {
			return $string;
		} );
		FunctionMocker::replace( 'sanitize_html_class', function ( $string ) {
			return $string;
		} );
	}

	protected function _tearDownAfterClass(): void {
		FunctionMocker::tearDown();
	}

	public function test_merges_attributes() {
		$twig    = \Mockery::mock( Environment::class );
		$factory = \Mockery::mock( Component_Factory::class );

		$context             = new class ( $twig, $factory ) extends Context {
			protected $properties = [
				'test_attrs' => [
					Context::DEFAULT          => [],
					Context::MERGE_ATTRIBUTES => [ 'data-test' => 'test-value' ],
				],
			];
		};
		$context->test_attrs = [ 'data-another' => 'another-value' ];

		$data = $context->get_data();
		$this->assertEquals( "data-test='test-value' data-another='another-value'", $data['test_attrs'] );
	}

	public function test_override_merge_attributes() {
		$twig    = \Mockery::mock( Environment::class );
		$factory = \Mockery::mock( Component_Factory::class );

		$context             = new class ( $twig, $factory ) extends Context {
			protected $properties = [
				'test_attrs' => [
					Context::DEFAULT          => [],
					Context::MERGE_ATTRIBUTES => [ 'data-test' => 'test-value' ],
				],
			];
		};
		$context->test_attrs = [ 'data-another' => 'another-value' ];
		$context->disable_merge( 'test_attrs' );

		$data = $context->get_data();
		$this->assertEquals( "data-another='another-value'", $data['test_attrs'] );
	}

	public function test_merges_classes() {
		$twig    = \Mockery::mock( Environment::class );
		$factory = \Mockery::mock( Component_Factory::class );

		$context               = new class ( $twig, $factory ) extends Context {
			protected $properties = [
				'test_classes' => [
					Context::DEFAULT       => [],
					Context::MERGE_CLASSES => [ 'test-value' ],
				],
			];
		};
		$context->test_classes = [ 'another-value' ];

		$data = $context->get_data();
		$this->assertEquals( "test-value another-value", $data['test_classes'] );
	}

	public function test_override_merge_classes() {
		$twig    = \Mockery::mock( Environment::class );
		$factory = \Mockery::mock( Component_Factory::class );

		$context               = new class ( $twig, $factory ) extends Context {
			protected $properties = [
				'test_classes' => [
					Context::DEFAULT       => [],
					Context::MERGE_CLASSES => [ 'test-value' ],
				],
			];
		};
		$context->test_classes = [ 'another-value' ];
		$context->disable_merge( 'test_classes' );

		$data = $context->get_data();
		$this->assertEquals( "another-value", $data['test_classes'] );
	}

	public function test_not_forced_merge_classes() {
		$twig    = \Mockery::mock( Environment::class );
		$factory = \Mockery::mock( Component_Factory::class );

		$context               = new class ( $twig, $factory ) extends Context {
			protected $properties = [
				'test_classes' => [
					Context::DEFAULT       => [ 'test-value' ],
					Context::MERGE_CLASSES => [],
				],
			];
		};
		$context->test_classes = [ 'another-value' ];

		$data = $context->get_data();
		$this->assertEquals( "another-value", $data['test_classes'] );
	}

	public function test_assign_props_on_construct() {
		$twig    = \Mockery::mock( Environment::class );
		$factory = \Mockery::mock( Component_Factory::class );

		$context = new class ( $twig, $factory, [ 'test_prop1' => 'test3' ] ) extends Context {
			protected $properties = [
				'test_prop1' => [
					Context::DEFAULT => 'test1',
				],
				'test_prop2' => [
					Context::DEFAULT => 'test2',
				],
			];
		};

		$data = $context->get_data();
		$this->assertEquals( 'test3', $data['test_prop1'] );
		$this->assertEquals( 'test2', $data['test_prop2'] );
	}
}
