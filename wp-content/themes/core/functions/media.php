<?php
/**
 * Functions: Media
 *
 * Functions for dealing with media & embeds
 *
 * @since core 1.0
 */


add_filter( 'the_content', 'customize_wp_image_output', 12, 1 );
add_action( 'init', 'disable_responsive_images' );


// WP SEO
if( function_exists( 'wpseo_auto_load' ) ) {
	add_filter( 'wpseo_opengraph_image_size', 'customize_wpseo_image_size' );
}


/*
 * Theme image sizes
 */

// Full (to cut down on max-size, trim if we can)
add_image_size( 'core-full', 1600, 0, true );

// For social sharing
add_image_size( 'social-share', 1200, 630, true );


/**
 * Customize WP SEO image size
 */

function customize_wpseo_image_size( $size ) {
    return 'social-share';
}


/**
 * Customize WP image output
 */

function customize_wp_image_output( $html ) {

	$regex = '#((<\s*figure[^>]*?>)(.*?))?((<\s*a\s[^>]*?>)(.*?))?((<\s*img[^>]+)(src\s*=\s*"[^"]+")([^>]+>))((.*?)(</a>))?((.*?)(</figure>))?#i';
	$html = preg_replace_callback( $regex, 'image_wrap_regex_callback', $html );
	return $html;

}

function image_wrap_regex_callback( $matches ) {

	$full_match  = $matches[0];
	$the_figure  = $matches[2];
	$the_img     = $matches[7];
	$the_img_src = $matches[9];

	$updated_image = str_replace( $the_img_src, $the_img_src, $the_img );

	if ( empty( $the_figure ) ) {
		$full_match = sprintf( '<figure class="wp-image-wrap">%s</figure>', $full_match );
	}

	return str_replace( $the_img, $updated_image, $full_match );

}


/**
 * Disable WP responsive images output (temporary for now)
 */

function disable_responsive_images() {

	add_filter( 'wp_get_attachment_image_attributes', function ( $attr ) {
		if ( isset( $attr['sizes'] ) ) {
			unset( $attr['sizes'] );
		}

		if ( isset( $attr['srcset'] ) ) {
			unset( $attr['srcset'] );
		}

		return $attr;

	}, 999 );

	add_filter( 'wp_calculate_image_sizes', '__return_false', 999 );
	add_filter( 'wp_calculate_image_srcset', '__return_false', 999 );
	remove_filter( 'the_content', 'wp_make_content_images_responsive' );

}