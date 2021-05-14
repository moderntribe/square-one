<?php

namespace Tribe\Project\Blocks;

use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Field;
use Tribe\Tests\Test_Case;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Block_Registrar;
use Tribe\Project\Blocks\Types\Base_Model;

class Block_Field_Injection_Test extends Test_Case {

	public function test_it_can_inject_fields_into_a_block() {
		$block = new class extends Block_Config {

			public const NAME = 'test_block';

			public function add_block() {
				$this->set_block( new Block( self::NAME, [
					'title'       => __( 'Test Block', 'tribe' ),
					'description' => __( 'A test block', 'tribe' ),
					'icon'        => 'sticky',
					'keywords'    => [],
					'category'    => 'layout',
					'supports'    => [
						'align'  => false,
						'anchor' => true,
					],
				] ) );
			}

			protected function add_fields() {
				$this->add_field( new Field( self::NAME . '_text', [
					'label' => 'Text Field',
					'name'  => 'text',
					'type'  => 'text',
				] ) );
			}

		};

		add_filter( 'tribe/block/register/fields', function ( $fields, Block_Config $block ): array {
			$new_fields = [
				new Field( 'injected_from_outside', [
					'label' => 'Injected Field',
					'name'  => 'from_outside',
					'type'  => 'text',
				] ),
			];

			return array_merge( $fields, $new_fields );
		}, 9, 2 );

		$block_registrar = new Block_Registrar();
		$block_registrar->register( $block );

		$expected = [
			'field_test_block_text'       => [
				'key'        => 'field_test_block_text',
				'name'       => 'text',
				'type'       => 'text',
				'parent'     => 'group_test_block',
				'label'      => 'Text Field',
				'menu_order' => 0,
			],
			'field_injected_from_outside' => [
				'key'        => 'field_injected_from_outside',
				'name'       => 'from_outside',
				'type'       => 'text',
				'parent'     => 'group_test_block',
				'label'      => 'Injected Field',
				'menu_order' => 1,
			],
		];

		$fields = acf_get_local_fields( 'group_test_block' );

		$this->assertCount( 2, $fields );
		$this->assertSame( $expected, $fields );
	}

	public function test_it_can_inject_fields_into_a_model() {

		add_filter( 'tribe/block/model/data', function ( $data, Base_Model $model ): array {
			return array_merge_recursive( $data, [
				'classes' => [ 'test' ],
			] );
		}, 10, 2 );

		$block = [
			'mode' => 'preview',
			'data' => [],
			'name' => 'acf/test_block',
			'id'   => 'block_123456',
		];

		$model = new class( $block ) extends Base_Model {

			protected function set_data(): array {
				return [
					'attrs' => [ 'something' ],
				];
			}

		};

		$expected = [
			'attrs'   => [ 'something' ],
			'classes' => [ 'test' ],
		];

		$this->assertSame( $expected, $model->get_data() );
	}

}
