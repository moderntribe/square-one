<?php
declare( strict_types=1 );

namespace Tribe\Project\Blocks;

use Psr\Container\ContainerInterface;
use Tribe\Gutenpanels\Registration\Registry;
use Tribe\Libs\Container\Subscriber_Interface;

class Blocks_Subscriber implements Subscriber_Interface {
	public function register( ContainerInterface $container ): void {
		add_action( 'tribe/gutenpanels/register', static function ( Registry $registry ) use ( $container ) {
			foreach ( $container->get( Blocks_Definer::TYPES ) as $type ) {
				/** @var Block_Type_Config $type */
				$registry->register( $type->build() );
			}
		}, 10, 1 );

		add_action( 'tribe/gutenpanels/block/render', static function ( $prefiltered, $attributes, $content, $block_type ) use ( $container ) {
			return $container->get( Render_Filter::class )->render( $prefiltered, $attributes, $content, $block_type );
		}, 10, 4 );

		add_filter( 'tribe/project/blocks/blacklist', static function( $types ) use ( $container ) {
			return $container->get( Allowed_Blocks::class )->filter_block_blacklist( $types );
		}, 10, 2 );
	}

}
