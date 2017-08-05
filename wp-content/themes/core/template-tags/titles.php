<?php

/**
 * Template Tags: Content Output
 */


/**
 * Sets up the proper title
 *
 * @since core 1.0
 * @return string
 */

function get_page_title() {
	$title = new \Tribe\Project\Theme\Page_Title();
	return $title->get_title();
}

/**
 * Output proper title
 *
 * @since core 1.0
 * @return string
 */
function the_page_title( $wrapper = true ) {

	$title = get_page_title();

	if ( $wrapper ) {
		$title = '<h1 class="page-title">' . esc_html( $title ) . '</h1>';
	}

	if ( ! empty( $title ) ) {
		echo $title;
	}

}
