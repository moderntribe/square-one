<?php

/**
 * Template Tags: Content Output
 */


/**
 * Sets up the proper title
 *
 * @todo  Remove this function
 *
 * @since core 1.0
 * @return string
 */

function get_page_title() {

	if ( is_front_page() ) {
		return '';
	}

	// Blog
	if ( is_home() ) {
		return __( 'Blog', 'tribe' );
	}

	// Search
	if ( is_search() ) {
		return __( 'Search Results', 'tribe' );
	}

	// 404
	if ( is_404() ) {
		return __( 'Page Not Found (404)', 'tribe' );
	}

	// Singular
	if ( is_singular() ) {
		return get_the_title();
	}

	// Archives
	return get_the_archive_title();
}
