<?php

/**
 * Output panel title, built for SEO
 *
 * @param string $title   Panel title
 * @param string $classes Any CSS classes to apply to the title tag.
 *
 * @param null   $data_name
 * @param bool   $data_livetext
 *
 * @param int    $depth
 * @param int    $index
 *
 * @return string
 * @since tribe-square-one 1.0
 */

function the_panel_title( $title = null, $classes = 'panel__title', $data_name = null, $data_livetext = false, $depth = 0, $index = 0 ) {

	if ( empty( $title ) && ! is_panel_preview() ) {
		return;
	}

	static $panel_title = '';

	$h_level = 'h2';

	if ( empty( $panel_title ) && ( get_the_content() == '' && ! is_front_page() ) ) {
		$h_level = 'h1';
	}

	$class      = ( ! empty( $classes ) ) ? ' class="' . $classes . '"' : '';

	$data_attrs = ( ! empty( $data_name ) ) ? ' data-name="' . $data_name . '"' : '';
	$data_attrs .= ( $data_livetext ) ? ' data-livetext' : '';
	$data_attrs .= ( $data_livetext ) ? sprintf( ' data-depth="%s"', $depth ) : '';
	$data_attrs .= ( $data_livetext ) ? sprintf( ' data-index="%s"', $index ) : '';

	$panel_title = sprintf(
	'<%1$s%2$s%3$s>%4$s</%1$s>',
		$h_level,
		$class,
		$data_attrs,
		$title
	);

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
 * @since tribe-square-one 1.0
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
 * @since tribe-square-one 1.0
 */
function the_panel_link( $link, $alt_label = 'Read More', $force_alt_label = false, $css_class = '' ) {
	if ( isset( $link['url'] ) ) {
		echo get_panel_link( $link, $alt_label, $force_alt_label, $css_class );
	}
}
