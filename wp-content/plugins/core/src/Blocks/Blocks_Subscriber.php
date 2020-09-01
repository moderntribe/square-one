<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks;

use Tribe\Libs\ACF\Block_Registrar;

use Tribe\Libs\ACF\Block_Renderer;
use Tribe\Libs\Container\Abstract_Subscriber;

class Blocks_Subscriber extends Abstract_Subscriber {

	public function register(): void {
		add_action( 'acf/init', function () {
			foreach ( $this->container->get( Blocks_Definer::TYPES ) as $type ) {
				$this->container->get( Block_Registrar::class )->register( $type );
			}
		}, 10, 1 );

		add_action( 'tribe/project/block/render', function ( ...$args ) {
			$this->container->get( Block_Renderer::class )->render_template( ...$args );
		}, 10, 4 );

		add_filter( 'allowed_block_types', function ( $types, $post ) {
			return $this->container->get( Allowed_Blocks::class )->register_allowed_blocks( $types, $post );
		}, 10, 2 );

		add_action( 'after_setup_theme', function () {
			$this->container->get( Theme_Support::class )->register_theme_supports();

			foreach ( $this->container->get( Blocks_Definer::STYLES ) as $style_override ) {
				/** @var Block_Style_Override $style_override */
				$style_override->register();
			}
		}, 10, 0 );
	}
}
