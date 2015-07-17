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

