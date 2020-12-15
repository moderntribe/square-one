<?php

namespace Tribe\Project\Templates\Components;

class Abstract_ControllerTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @var \IntegrationTester
	 */
	protected $tester;

	// Tests
	public function test_parses_args() {
		$args = [
			'one'   => 'this is a string',
			'two'   => [ 'this', 'is', 'an', 'array' ],
			'three' => [ 'value' ],
		];

		$controller = new class( $args ) extends Abstract_Controller {
			public array $args;

			public function __construct( array $args ) {
				$this->args = $this->parse_args( $args );
			}

			protected function defaults(): array {
				return [
					'one'   => 'default string',
					'two'   => [ 'default', 'array' ],
					'three' => [ 'optional' ],
					'four'  => 'something',
				];
			}

			protected function required(): array {
				return [
					'three' => [ 'required' ],
				];
			}
		};

		self::assertEquals( 'this is a string', $controller->args['one'] );
		self::assertEquals( [ 'this', 'is', 'an', 'array' ], $controller->args['two'] );
		self::assertEquals( [ 'value', 'required' ], $controller->args['three'] );
		self::assertEquals( 'something', $controller->args['four'] );
	}
}
