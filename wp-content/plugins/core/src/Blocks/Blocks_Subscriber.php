<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks;

use Psr\Container\ContainerInterface;
use Tribe\Gutenpanels\Registration\Registry;
use Tribe\Libs\Container\Subscriber_Interface;

class Blocks_Subscriber implements Subscriber_Interface {
	public function register( ContainerInterface $container ): void {
		add_action( 'tribe/gutenpanels/register', function ( Registry $registry ) use ( $container ) {
			foreach ( $container->get( Blocks_Definer::TYPES ) as $type ) {
				/** @var Block_Type_Config $type */
				$registry->register( $type->build() );
			}
		}, 10, 1 );

		add_action( 'tribe/gutenpanels/block/render', function ( $prefiltered, $attributes, $content, $block_type ) use ( $container ) {
			return $container->get( Render_Filter::class )->render( $prefiltered, $attributes, $content, $block_type );
		}, 10, 4 );
	}

}
