<?php declare(strict_types=1);

namespace Tribe\Project\Block_Middleware\Pipelines;

use Ds\Map;
use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\ACF\Repeater;
use Tribe\Libs\ACF\Traits\With_Field_Prefix;
use Tribe\Libs\Pipeline\Pipeline;
use Tribe\Project\Block_Middleware\Contracts\Has_Middleware_Params;
use Tribe\Project\Block_Middleware\Guards\Block_Field_Middleware_Guard;
use Tribe\Tests\Fixtures\Description_Field_Middleware;
use Tribe\Tests\Fixtures\Repeater_Field_Middleware;
use Tribe\Tests\Fixtures\Subtitle_Field_Middleware;
use Tribe\Tests\Test_Case;

class AddFieldsPipelineTest extends Test_Case {

	public function test_it_processes_block_fields_middleware_pipeline(): void {
		$blockOne = new class extends Block_Config {
			public const NAME = 'block_one';

			public const FIELD_TITLE = 'title';

			public function add_block() {
				$this->add_field( new Field( self::NAME . '_' . self::FIELD_TITLE, [
					'name' => self::FIELD_TITLE,
					'type' => 'text'
				] ) );
			}
		};

		$attributes = $blockOne->get_field_group()->get_attributes();

		$this->assertSame( 'group_block_one', $attributes['key'] );
		$this->assertSame( [
			[
				'name' => 'title',
				'type' => 'text',
				'key'  => 'field_block_one_title',
			],
		], $attributes['fields'] );

		$block_field_guard = new Block_Field_Middleware_Guard( new Map( [
			get_class( $blockOne ) => true,
		] ) );

		$pipeline = $this->container->get( Pipeline::class )
		                            ->via( 'add_fields' )
		                            ->through( [
			                            new Description_Field_Middleware( $block_field_guard ),
			                            new Subtitle_Field_Middleware( $block_field_guard ),
		                            ] );

		$processed_block = ( new Add_Fields_Pipeline( $pipeline ) )->process( $blockOne );

		$attributes = $processed_block->get_field_group()->get_attributes();

		$this->assertSame( 'group_block_one', $attributes['key'] );
		$this->assertSame( [
			[
				'name' => 'title',
				'type' => 'text',
				'key'  => 'field_block_one_title',
			],
			[
				'name' => Description_Field_Middleware::FIELD,
				'type' => 'text',
				'key'  => sprintf( 'field_%s_%s', Description_Field_Middleware::NAME, Description_Field_Middleware::FIELD ),
			],
			[
				'name' => Subtitle_Field_Middleware::FIELD,
				'type' => 'text',
				'key'  => sprintf( 'field_%s_%s', Subtitle_Field_Middleware::NAME, Subtitle_Field_Middleware::FIELD )
			],
		], $attributes['fields'] );
	}

	public function test_it_processes_specific_block_fields_middleware_pipeline(): void {
		$blockOne = new class extends Block_Config {
			public const NAME = 'block_one';

			public const FIELD_TITLE = 'title';

			public function add_block() {
				$this->add_field( new Field( self::NAME . '_' . self::FIELD_TITLE, [
					'name' => self::FIELD_TITLE,
					'type' => 'text'
				] ) );
			}
		};

		$attributes = $blockOne->get_field_group()->get_attributes();

		$this->assertSame( 'group_block_one', $attributes['key'] );
		$this->assertSame( [
			[
				'name' => 'title',
				'type' => 'text',
				'key'  => 'field_block_one_title',
			],
		], $attributes['fields'] );

		$block_field_guard = new Block_Field_Middleware_Guard( new Map( [
			get_class( $blockOne ) => [
				Subtitle_Field_Middleware::class,
			],
		] ) );

		$pipeline = $this->container->get( Pipeline::class )
		                            ->via( 'add_fields' )
		                            ->through( [
			                            new Description_Field_Middleware( $block_field_guard ),
			                            new Subtitle_Field_Middleware( $block_field_guard ),
		                            ] );

		$processed_block = ( new Add_Fields_Pipeline( $pipeline ) )->process( $blockOne );

		$attributes = $processed_block->get_field_group()->get_attributes();

		$this->assertSame( 'group_block_one', $attributes['key'] );
		$this->assertSame( [
			[
				'name' => 'title',
				'type' => 'text',
				'key'  => 'field_block_one_title',
			],
			[
				'name' => Subtitle_Field_Middleware::FIELD,
				'type' => 'text',
				'key'  => sprintf( 'field_%s_%s', Subtitle_Field_Middleware::NAME, Subtitle_Field_Middleware::FIELD )
			],
		], $attributes['fields'] );
	}

	public function test_it_processes_block_fields_middleware_pipeline_with_additional_parameters(): void {
		$block = new class extends Block_Config implements Has_Middleware_Params {

			use With_Field_Prefix;

			public const NAME = 'block_three';

			public const FIELD_PEOPLE    = 'people';
			public const FIELD_NAME      = 'name';
			public const FIELD_EMPLOYEES = 'employees';

			public function add_block() {
				$this->set_block( new Block( self::NAME, [
					'title' => 'Block Three',
				] ) );
			}

			public function add_fields() {
				$repeater = new Repeater( self::NAME . '_' . self::FIELD_PEOPLE );

				$name = new Field( self::NAME . '_' . self::FIELD_NAME, [
					'name' => self::FIELD_NAME,
					'type' => 'text'
				] );

				$repeater->add_field( $name );

				$this->add_field( $repeater );

				$employee_repeater = new Repeater( self::NAME . '_' . self::FIELD_EMPLOYEES );

				$employee_repeater->add_field( $name );

				$this->add_field( $employee_repeater );
			}

			public function get_middleware_params(): array {
				return [
					[
						Repeater_Field_Middleware::MIDDLEWARE_KEY => [
							$this->get_field_key( self::FIELD_PEOPLE ),
							$this->get_field_key( self::FIELD_EMPLOYEES ),
						],
					],
				];
			}

		};

		$attributes = $block->get_field_group()->get_attributes();

		$this->assertSame( 'group_block_three', $attributes['key'] );
		$this->assertSame( [
			[
				'key'  => 'field_block_three_people',
				'type' => 'repeater',
				'sub_fields' => [
					[
						'name' => 'name',
						'type' => 'text',
						'key'  => 'field_block_three_name',
					],
				],
			],
			[
				'key'  => 'field_block_three_employees',
				'type' => 'repeater',
				'sub_fields' => [
					[
						'name' => 'name',
						'type' => 'text',
						'key'  => 'field_block_three_name',
					],
				],
			],
		], $attributes['fields'] );

		$block_field_guard = new Block_Field_Middleware_Guard( new Map( [
			get_class( $block ) => true,
		] ) );

		$pipeline = $this->container->get( Pipeline::class )
		                            ->via( 'add_fields' )
		                            ->through( [
			                            new Repeater_Field_Middleware( $block_field_guard ),
		                            ] );

		$processed_block = ( new Add_Fields_Pipeline( $pipeline ) )->process( $block, $block->get_middleware_params() );

		$attributes = $processed_block->get_field_group()->get_attributes();

		$this->assertSame( 'group_block_three', $attributes['key'] );
		$this->assertSame( [
			[
				'key'  => 'field_block_three_people',
				'type' => 'repeater',
				'sub_fields' => [
					[
						'name' => 'name',
						'type' => 'text',
						'key'  => 'field_block_three_name',
					],
					[
						'name' => 'title',
						'type' => 'text',
						'key'  => 'field_block_three_title',
					],
				],
			],
			[
				'key'  => 'field_block_three_employees',
				'type' => 'repeater',
				'sub_fields' => [
					[
						'name' => 'name',
						'type' => 'text',
						'key'  => 'field_block_three_name',
					],
					[
						'name' => 'title',
						'type' => 'text',
						'key'  => 'field_block_three_title',
					],
				],
			]
		], $attributes['fields'] );
	}

}
