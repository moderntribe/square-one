<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks;

use Tribe\Gutenpanels\Registration\Registry;
use Tribe\Libs\Container\Abstract_Subscriber;

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

		add_filter( 'tribe/project/blocks/blacklist', function( $types ) {
			return $this->container->get( Allowed_Blocks::class )->filter_block_blacklist( $types );
		}, 10, 2 );

		add_action( 'after_setup_theme', function () {
			$this->container->get( Theme_Support::class )->register_theme_supports();
		}, 10, 0 );
	}

}
