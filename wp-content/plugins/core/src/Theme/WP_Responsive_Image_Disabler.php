<?php


namespace Tribe\Project\Theme;


class WP_Responsive_Image_Disabler {
	public function hook() {
		add_filter( 'wp_get_attachment_image_attributes', [ $this, 'filter_image_attributes' ], 999, 1 );

		add_filter( 'wp_calculate_image_sizes', '__return_empty_array', 999 );
		add_filter( 'wp_calculate_image_srcset', '__return_empty_array', 999 );

		add_action( 'after_setup_theme', function () {
			remove_filter( 'the_content', 'wp_make_content_images_responsive' );
		}, 10, 0 );
	}

	public function filter_image_attributes( $attr ) {
		if ( isset( $attr[ 'sizes' ] ) ) {
			unset( $attr[ 'sizes' ] );
		}

		if ( isset( $attr[ 'srcset' ] ) ) {
			unset( $attr[ 'srcset' ] );
		}

		return $attr;
	}
}