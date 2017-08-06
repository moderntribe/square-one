<?php

/**
 * Output panel title, built for SEO
 *
 * @param string $title   Panel title
 * @param string $classes Any CSS classes to apply to the title tag.
 *
 * @since tribe-square-one 1.0
 * @return string
 */

function the_panel_title( $title = null, $classes = 'panel-title', $data_name = null, $data_livetext = false, $depth = 0, $index = 0 ) {

	if ( empty( $title ) && ! is_panel_preview() ) {
		return;
	}

	static $panel_title = '';

	// Panel Preview AJAX calls send along an index value for determining positon.
	if ( is_panel_preview() && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		$preview_index = (integer) filter_input( INPUT_POST, 'index', FILTER_SANITIZE_NUMBER_INT );
	} else {
		$preview_index = null;
	}

	$is_first_panel = ( $preview_index === 0 || ( is_null( $preview_index ) && empty( $panel_title ) ) );

	$h_level = 'h2';

	if ( get_the_content() === '' && $is_first_panel ) {
		$h_level = 'h1';
	}

	$attr = ( ! empty( $classes ) ) ? ' class="'. $classes .'"' : '';
	$data_attrs = ( ! empty( $data_name ) ) ? ' data-name="'. $data_name . '"' : '';
	$data_attrs .= ( $data_livetext ) ? ' data-livetext' : '';
	$data_attrs .= ( $data_livetext ) ? sprintf( ' data-depth="%s"', $depth ) : '';
	$data_attrs .= ( $data_livetext ) ? sprintf( ' data-index="%s"', $index ) : '';

	$panel_title = sprintf(
		'<%1$s%2$s%3$s>%4$s</%1$s>',
		$h_level,
		$attr,
		$data_attrs,
		$title
	);

	return $panel_title;
}

/**
 * Return an HTML <a> Link based on the Panel Link field settings
 *
 * @param array $link    The panel link field values array.
 * @param array $options An array of optional attributes & values to apply to the link element markup.
 *
 * @return bool|string   The link tag HTML.
 *
 * @since tribe-square-one 1.0
 */
function get_panel_link( $link, $options = [] ) {
	if ( ! isset( $link['url'] ) ) {
		return false;
	}

	$defaults = [
		'css_class'       => '',            // String: Space-separated CSS classes to apply to the HTML element
		'alt_label'       => 'Read More',   // String: The default label to use for the link
		'force_alt_label' => false,         // Bool: Should the alt label override the label set on the link field?
	];

	$options = wp_parse_args( $options, $defaults );

	return sprintf(
		'<a href="%1$s"%2$s%3$s>%4$s</a>',
		esc_url( $link[ 'url' ] ),
		empty( $options[ 'css_class' ] ) ? '' : ' class="' . esc_attr( $options[ 'css_class' ] ) . '"',
		empty( $link['target'] ) ? '' : ' target="' . esc_attr( $link['target'] ) . '"',
		empty( $link['label'] ) || $options[ 'force_alt_label' ] ? $options[ 'alt_label' ] : $link['label']
	);
}

/**
 * Output an HTML <a> Link based on the Panel Link field settings
 *
 * @param array $link    The panel link field values array.
 * @param array $options An array of optional attributes & values to apply to the link element markup.
 *
 * @since tribe-square-one 1.0
 */
function the_panel_link( $link, $options = [] ) {
	if ( isset( $link['url'] ) ) {
		echo get_panel_link( $link, $options );
	}
}
