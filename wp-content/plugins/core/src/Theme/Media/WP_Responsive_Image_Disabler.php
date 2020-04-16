<?php


namespace Tribe\Project\Theme\Media;


class WP_Responsive_Image_Disabler {

	/**
	 * Remove the responsibe image attributes from images
	 *
	 * @param array $attr
	 *
	 * @return array
	 * @filter wp_get_attachment_image_attributes
	 */
	public function filter_image_attributes( $attr ) {
		if ( isset( $attr['sizes'] ) ) {
			unset( $attr['sizes'] );
		}

		if ( isset( $attr['srcset'] ) ) {
			unset( $attr['srcset'] );
		}

		return $attr;
	}

	/**
	 * Bypass WordPress filters that set up responsive image attributes
	 *
	 * @return void
	 * @action after_setup_theme
	 */
	public function disable_wordpress_filters(): void {
		add_filter( 'wp_calculate_image_sizes', '__return_empty_array', 999 );
		add_filter( 'wp_calculate_image_srcset', '__return_empty_array', 999 );
		remove_filter( 'the_content', 'wp_make_content_images_responsive' );
	}
}
