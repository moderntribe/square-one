<?php
declare( strict_types=1 );

namespace Tribe\Project\Shortcodes;

use Psr\Container\ContainerInterface;
use Tribe\Libs\Container\Subscriber_Interface;

class Shortcodes_Subscriber implements Subscriber_Interface {
	public function register( ContainerInterface $container ): void {

		add_filter( 'post_gallery', function ( $output, $attr, $instance ) use ( $container ) {
			// No attrs defined will result in a string instead of array.
			if ( ! is_array( $attr ) ) {
				$attr = [];
			}

			return $container->get( Gallery::class )->render( $attr, $instance );
		}, 10, 3 );

	}
}
