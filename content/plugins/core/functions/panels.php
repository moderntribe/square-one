<?php

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

	if ( empty( $panel_title ) && ( ! is_front_page() ) ) {
		$panel_title = '<h1'. $class .'>' . $title . '</h1>';
	} else {
		$panel_title = '<h2'. $class .'>' . $title . '</h2>';
	}

	echo $panel_title;

}

/**
 * Return an HTML <a> Link based on the Panel Link field settings
 *
 * @param array         $link               The panel link field values array.
 * @param string        $alt_label          An alternate or default label to use.
 * @param bool          $force_alt_label    Force the alternate label display?
 * @param string        $css_class          Any CSS classes to apply to the <a> tag.
 *
 * @return bool|string  $tag                The link tag HTML.
 *
 * @since conroe 1.0
 */
function get_panel_link( $link, $alt_label = 'Read More', $force_alt_label = false, $css_class = '' ) {

	$tag    = false;
	$url    = ( empty( $link['url'] ) ? false : $link['url'] );
	$label  = ( empty( $link['label'] ) || $force_alt_label ? $alt_label : $link['label'] );
	$target = ( empty( $link['target'] ) ? '' : ' target="' . $link['target'] . '"' );
	$class  = ( empty( $css_class ) ? '' : ' class="' . esc_attr( $css_class ) . '"' );

	if ( $url ) {
		$tag = '<a' . $class . ' href="' . $url . '"' . $target . '>' . $label . '</a>';
	}

	return $tag;

}

/**
 * Output an HTML <a> Link based on the Panel Link field settings
 *
 * @param array         $link               The panel link field values array.
 * @param string        $alt_label          An alternate or default label to use.
 * @param bool          $force_alt_label    Force the alternate label display?
 * @param string        $css_class          Any CSS classes to apply to the <a> tag.
 *
 * @since conroe 1.0
 */
function the_panel_link( $link, $alt_label = 'Read More', $force_alt_label = false, $css_class = '' ) {
	if ( isset( $link['url'] ) ) {
		echo get_panel_link( $link, $alt_label, $force_alt_label, $css_class );
	}
}
