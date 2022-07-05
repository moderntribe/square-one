<?php declare(strict_types=1);

namespace Tribe\Project\Block_Middleware;

use Ds\Map;
use Tribe\Libs\ACF\Block;
use Tribe\Libs\ACF\Block_Config;
use Tribe\Libs\ACF\Block_Registrar;
use Tribe\Libs\ACF\Field;
use Tribe\Libs\Pipeline\Pipeline;
use Tribe\Project\Block_Middleware\Guards\Block_Field_Middleware_Guard;
use Tribe\Project\Block_Middleware\Pipelines\Add_Fields_Pipeline;
use Tribe\Tests\Fixtures\Description_Field_Middleware;
use Tribe\Tests\Fixtures\Subtitle_Field_Middleware;
use Tribe\Tests\Test_Case;

final class BlockFieldRegistrarTest extends Test_Case {

	public function test_it_registers_blocks_after_passing_through_field_middleware(): void {
		$blockOne = new class extends Block_Config {
			public const NAME = 'test_block';

			public const FIELD_TITLE = 'title';

			public function add_block() {
				$this->set_block( new Block( self::NAME, [
					'title'       => esc_html__( 'A test block', 'tribe' ),
					'description' => esc_html__( 'A block that should pass through field middleware', 'tribe' ),
				] ) );
			}

			public function add_fields() {
				$this->add_field( new Field( self::NAME . '_' . self::FIELD_TITLE, [
					'name' => self::FIELD_TITLE,
					'type' => 'text'
				] ) );
			}
		};

		$attributes = $blockOne->get_field_group()->get_attributes();

		$this->assertSame( 'group_test_block', $attributes['key'] );
		$this->assertSame( [
			[
				'name' => 'title',
				'type' => 'text',
				'key'  => 'field_test_block_title',
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

		$middleware      = new Add_Fields_Pipeline( $pipeline );
		$block_registrar = new Block_Registrar();
		$blocks = [
			$blockOne,
		];

		$field_registrar = new Block_Field_Registrar( $block_registrar, $middleware, $blocks );
		$field_registrar->register();

		$acf_blocks = acf_get_block_types();

		$this->assertArrayHasKey( 'acf/test-block', $acf_blocks );
		$this->assertSame( 'acf/test-block', $acf_blocks['acf/test-block']['name'] );
		$this->assertSame( 'A test block', $acf_blocks['acf/test-block']['title'] );
	}

}
