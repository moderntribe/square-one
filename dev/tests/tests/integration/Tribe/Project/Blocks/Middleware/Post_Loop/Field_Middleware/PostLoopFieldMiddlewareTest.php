<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Post_Loop\Field_Middleware;

use Ds\Map;
use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Field_Section;
use Tribe\Libs\Pipeline\Pipeline;
use Tribe\Project\Block_Middleware\Contracts\Has_Middleware_Params;
use Tribe\Project\Block_Middleware\Guards\Block_Field_Middleware_Guard;
use Tribe\Project\Block_Middleware\Pipelines\Add_Fields_Pipeline;
use Tribe\Project\Blocks\Middleware\Post_Loop\Config\Post_Loop_Field_Config;
use Tribe\Tests\Test_Case;

final class PostLoopFieldMiddlewareTest extends Test_Case {

	public function test_it_injects_the_post_loop_field_into_a_block(): void {
		$block = new class extends Block_Config implements Has_Middleware_Params {
			public const NAME = 'test_block';

			public const SECTION_CARDS = 's-cards';

			public function add_block() {
				$this->set_block( new Block( self::NAME, [
					'title' => esc_html__( 'A Test Block', 'tribe' ),
				] ) );
			}

			public function add_fields() {
				// Populated via Block Middleware
				$this->add_section( new Field_Section( self::SECTION_CARDS, esc_html__( 'Cards', 'tribe' ), 'accordion' ) );
			}

			/**
			 * Config the query post loop field for middleware.
			 *
			 * @return array{array{post_loop_field_config: \Tribe\Project\Blocks\Middleware\Post_Loop\Config\Post_Loop_Field_Config}}
			 */
			public function get_middleware_params(): array {
				$config             = new Post_Loop_Field_Config();
				$config->block_name = self::NAME;
				$config->group      = sprintf( 'section__%s', self::SECTION_CARDS );
				$config->limit_min  = 2;
				$config->limit_max  = 10;

				return [
					[
						Post_Loop_Field_Middleware::MIDDLEWARE_KEY => $config,
					],
				];
			}

		};

		$block_field_guard = new Block_Field_Middleware_Guard( new Map( [
			get_class( $block ) => [
				Post_Loop_Field_Middleware::class,
			],
		] ) );

		$pipeline = $this->container->make( Pipeline::class )
		                            ->via( 'add_fields' )
		                            ->through( [
			                            new Post_Loop_Field_Middleware( $block_field_guard ),
		                            ] );

		$processed_block = ( new Add_Fields_Pipeline( $pipeline ) )->process( $block, $block->get_middleware_params() );
		$attributes = $processed_block->get_field_group()->get_attributes();

		$this->assertSame( 'group_test_block', $attributes[ 'key'] );
		$this->assertSame( Post_Loop_Field_Middleware::NAME, $attributes['fields'][1]['name'] );
		$this->assertSame( sprintf( 'field_test_block_%s', Post_Loop_Field_Middleware::NAME ), $attributes['fields'][1]['key'] );
		$this->assertCount( 4, $attributes['fields'][1]['sub_fields'] );

		// Test manual post subfields were added to ensure the next test is doing proper assertions.
		$manual_repeater_sub_fields = $attributes['fields'][1]['sub_fields'][3]['sub_fields'];

		$field_names = array_column( $manual_repeater_sub_fields, 'name', 'name' );

		$this->assertArrayHasKey( Post_Loop_Field_Middleware::MANUAL_TITLE, $field_names );
		$this->assertArrayHasKey( Post_Loop_Field_Middleware::MANUAL_EXCERPT, $field_names );
	}

	public function test_it_does_not_add_hidden_post_loop_fields(): void {
		$block = new class extends Block_Config implements Has_Middleware_Params {
			public const NAME = 'test_block';

			public const SECTION_CARDS = 's-cards';

			public function add_block() {
				$this->set_block( new Block( self::NAME, [
					'title' => esc_html__( 'A Test Block', 'tribe' ),
				] ) );
			}

			public function add_fields() {
				// Populated via Block Middleware
				$this->add_section( new Field_Section( self::SECTION_CARDS, esc_html__( 'Cards', 'tribe' ), 'accordion' ) );
			}

			/**
			 * Config the query post loop field for middleware.
			 *
			 * @return array{array{post_loop_field_config: \Tribe\Project\Blocks\Middleware\Post_Loop\Config\Post_Loop_Field_Config}}
			 */
			public function get_middleware_params(): array {
				$config             = new Post_Loop_Field_Config();
				$config->block_name = self::NAME;
				$config->group      = sprintf( 'section__%s', self::SECTION_CARDS );
				$config->limit_min  = 2;
				$config->limit_max  = 10;
				$config->hide_fields = [
					Post_Loop_Field_Middleware::MANUAL_EXCERPT,
					Post_Loop_Field_Middleware::MANUAL_TITLE,
				];

				return [
					[
						Post_Loop_Field_Middleware::MIDDLEWARE_KEY => $config,
					],
				];
			}

		};

		$block_field_guard = new Block_Field_Middleware_Guard( new Map( [
			get_class( $block ) => [
				Post_Loop_Field_Middleware::class,
			],
		] ) );

		$pipeline = $this->container->make( Pipeline::class )
		                            ->via( 'add_fields' )
		                            ->through( [
			                            new Post_Loop_Field_Middleware( $block_field_guard ),
		                            ] );

		$processed_block = ( new Add_Fields_Pipeline( $pipeline ) )->process( $block, $block->get_middleware_params() );
		$attributes = $processed_block->get_field_group()->get_attributes();

		$this->assertSame( 'group_test_block', $attributes[ 'key'] );
		$this->assertSame( Post_Loop_Field_Middleware::NAME, $attributes['fields'][1]['name'] );
		$this->assertSame( sprintf( 'field_test_block_%s', Post_Loop_Field_Middleware::NAME ), $attributes['fields'][1]['key'] );

		$manual_repeater_sub_fields = $attributes['fields'][1]['sub_fields'][3]['sub_fields'];

		$field_names = array_column( $manual_repeater_sub_fields, 'name', 'name' );

		// Test the fields were not added to the block
		$this->assertArrayNotHasKey( Post_Loop_Field_Middleware::MANUAL_TITLE, $field_names );
		$this->assertArrayNotHasKey( Post_Loop_Field_Middleware::MANUAL_EXCERPT, $field_names );
	}

}
