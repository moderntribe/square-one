<?php
declare( strict_types=1 );

namespace Tribe\Project\Shortcodes;

use Tribe\Libs\Container\Abstract_Subscriber;

class Shortcodes_Subscriber extends Abstract_Subscriber {
	public function register(): void {
		add_filter( 'post_gallery', function ( $output, $attr, $instance ) {
			// No attrs defined will result in a string instead of array.
			if ( ! is_array( $attr ) ) {
				$attr = [];
			}

			return $this->container->get( Gallery::class )->render( $attr, $instance );
		}, 10, 3 );
	}
}
