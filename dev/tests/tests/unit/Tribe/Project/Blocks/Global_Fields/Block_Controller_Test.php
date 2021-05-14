<?php

namespace Tribe\Project\Blocks\Global_Fields;

use Codeception\Test\Unit;

class Block_Controller_Test extends Unit {

	public function test_global_field_block_allowance() {
		$blocks = [
			'test_block',
		];

		$controller = new Block_Controller( $blocks );

		$this->assertTrue( $controller->allowed( 'test_block' ) );
		$this->assertFalse( $controller->allowed( 'ignored_block' ) );
	}

	public function test_it_allows_global_fields_for_a_specific_block() {
		$meta = new class extends Block_Meta {

			public const NAME = 'test_block';

			protected function add_fields(): void {

			}

			public function get_fields(): array {
				return [];
			}
		};

		$model = new class extends Block_Model {

			protected function set_data(): array {
				return [];
			}

			public function get_data(): array {
				return [];
			}
		};

		$model->set_block_id( 'test_block' );

		$blocks = [
			'test_block' => [
				get_class( $meta ),
				get_class( $model ),
			],
			'test_block_with_different_blocks' => [
				'\Tribe\Project\Blocks\Global_Fields\Random_Meta.php',
				'\Tribe\Project\Blocks\Global_Fields\Random_Model.php',
			],
		];

		$controller = new Block_Controller( $blocks );

		$this->assertTrue( $controller->allowed( 'test_block' ) );
		$this->assertTrue( $controller->allows_specific_field_group( 'test_block', $meta ) );
		$this->assertTrue( $controller->allows_specific_field_group( 'test_block', $model ) );

		$this->assertFalse( $controller->allows_specific_field_group( 'test_block_with_different_blocks', $meta ) );
		$this->assertFalse( $controller->allows_specific_field_group( 'test_block_with_different_blocks', $model ) );

		$this->assertFalse( $controller->allowed( 'ignored_block' ) );
		// A specified block that doesn't exist will always return true, we assume you checked if it was allowed at all.
		$this->assertTrue( $controller->allows_specific_field_group( 'ignored_block', $meta ) );
		$this->assertTrue( $controller->allows_specific_field_group( 'ignored_block', $model ) );
	}

}
