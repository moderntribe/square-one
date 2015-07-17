<?php
/**
 * Functions: Theme
 *
 * Functions for powering the theme
 *
 * @since tribe-square-one 1.0
 */


// Theme Functions
add_filter( 'body_class', 'body_classes' );


/**
 * Add custom body classes
 */

function body_classes( $classes ) {

    if ( ! is_singular() ) {
        return $classes;
    }

    global $post;

    if ( empty( $post ) || empty( $post->ID ) ) {
        return $classes;
    }

    // Panel page check
	if( ( function_exists( 'have_panels' ) && have_panels() ) && empty( $post->post_content ) )
		$classes[] = 'is-panels-page';

    $classes[] = sanitize_html_class( $post->post_type . '-' . $post->post_name );

    return $classes;

}

