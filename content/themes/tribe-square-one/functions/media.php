<?php
/**
 * Functions: Media
 *
 * Functions for dealing with media & embeds
 *
 * @since tribe-square-one 1.0
 */


// WP SEO
if( function_exists( 'wpseo_auto_load' ) ) {
	add_filter( 'wpseo_opengraph_image_size', 'customize_wpseo_image_size' );
}

// Embeds
add_filter( 'embed_oembed_html', 'customize_embed_oembed_html', 99, 4 );


/*
 * Theme image sizes
 */

// Full (to cut down on max-size, trim if we can)
add_image_size( 'tribe-full', 1500, 0, 768, 0, true );

// For social sharing
add_image_size( 'social-share', 1200, 630, 0, 0, true );


/**
 * Customize WP SEO image size
 */

function customize_wpseo_image_size( $size ) {
    return 'social-share';
}


/**
 * Add wrapper around embeds to setup CSS for embed aspect ratios
 */

function customize_embed_oembed_html( $html, $url, $attr, $post_id ) {
	return '<div class="wp-embed"><div class="wp-embed-wrap">' . $html . '</div></div>';
}

