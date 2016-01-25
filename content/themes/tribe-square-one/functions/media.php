<?php
/**
 * Functions: Media
 *
 * Functions for dealing with media & embeds
 *
 * @since tribe-square-one 1.0
 */


add_filter( 'the_content', 'customize_wp_image_output', 12, 1 );


// WP SEO
if( function_exists( 'wpseo_auto_load' ) ) {
	add_filter( 'wpseo_opengraph_image_size', 'customize_wpseo_image_size' );
}


/*
 * Theme image sizes
 */

// Full (to cut down on max-size, trim if we can)
add_image_size( 'tribe-full', 1600, 0, true );

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