<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks;

use Tribe\Gutenpanels\Registration\Registry;
use Tribe\Libs\Container\Abstract_Subscriber;
use Tribe\Project\Controllers\Blocks\Block_Controller;

class Blocks_Subscriber extends Abstract_Subscriber {

	public function register(): void {
		add_action( 'tribe/gutenpanels/register', function ( Registry $registry ) {
			foreach ( $this->container->get( Blocks_Definer::TYPES ) as $type ) {
				/** @var Block_Type_Config $type */
				$registry->register( $type->build() );
			}
		}, 10, 1 );

		add_action( 'tribe/gutenpanels/block/render', function ( $prefiltered, $attributes, $content, $block_type ) {
			return $this->container->get( Render_Filter::class )->render( $prefiltered, $attributes, $content, $block_type );
		}, 10, 4 );

		add_filter( 'tribe/project/blocks/blacklist', function ( $types ) {
			return $this->container->get( Allowed_Blocks::class )->filter_block_blacklist( $types );
		}, 10, 2 );

		add_action( 'after_setup_theme', function () {
			$this->container->get( Theme_Support::class )->register_theme_supports();

			foreach ( $this->container->get( Blocks_Definer::STYLES ) as $style_override ) {
				/** @var Block_Style_Override $style_override */
				$style_override->register();
			}
		}, 10, 0 );

		add_filter( 'tribe/project/controllers/registered_block_controllers', [ $this, 'instantiate_block_controllers' ], 10, 1 );
	}

	/**
	 * Instantiate the block controllers for the various registered blocks.
	 *
	 * @param array $controllers
	 *
	 * @filter tribe/project/controllers/registered_block_controllers 10 1
	 *
	 * @return array
	 */
	public function instantiate_block_controllers( array $controllers ) {
		$registered_blocks  = $this->container->get( Blocks_Definer::TYPES );
		$mapped_controllers = $this->container->get( Blocks_Definer::CONTROLLER_MAP );

		foreach ( $registered_blocks as $block ) {
			/**
			 * If the block is already manually mapped to a controller, respect that mapping here.s
			 *
			 * @var Block_Type_Config $block
			 */
			if ( isset( $mapped_controllers[ $block::NAME ] ) ) {
				$controllers[ $block::NAME ] = $this->container->get( $mapped_controllers[ $block::NAME ] );
				continue;
			}

			try {
				$controller = $block->get_controller_for_block();
			} catch ( \ReflectionException $e ) {
				continue;
			}

			if ( $controller ) {
				$controllers[ $block::NAME ] = $this->container->get( $controller );
			}
		}

		return $controllers;
	}
}
