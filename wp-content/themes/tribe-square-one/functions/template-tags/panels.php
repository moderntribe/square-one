<?php
/**
 * Template Tags: Panels
 */


/**
 * Output panel title, built for SEO
 *
 * @since tribe-square-one 1.0
 * @return string
 */

function the_panel_title( $title = null, $classes = null ) {

	if ( empty( $title ) )
		return;

	static $panel_title = '';
	$class = ( ! empty( $classes ) ) ? ' class="'. $classes .'"' : '';

	if ( empty( $panel_title ) && ( get_the_content() == '' && ! is_front_page() ) ) {
		$panel_title = '<h1'. $class .'>' . $title . '</h1>';
	} else {
		$panel_title = '<h2'. $class .'>' . $title . '</h2>';
	}

	echo $panel_title;

}