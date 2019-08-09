<?php


namespace Tribe\Project\Service_Providers;

use Pimple\Container;
use Tribe\Project\Container\Service_Provider;
use Tribe\Project\Shortcodes\Gallery;

class Shortcode_Provider extends Service_Provider {

	public function register( Container $container ) {
		$this->gallery( $container );
	}

	protected function gallery( Container $container ) {
		$container['shortcode.gallery'] = function ( Container $container ) {
			return new Gallery();
		};

		add_filter( 'post_gallery', function ( $output, $attr, $instance ) use ( $container ) {
			// No attrs defined will result in a string instead of array.
			if ( ! is_array( $attr ) ) {
				$attr = [];
			}

			return $container['shortcode.gallery']->render( $attr, $instance );
		}, 10, 3 );
	}
}
