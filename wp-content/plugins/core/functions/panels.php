<?php

/**
 * Output panel title, built for SEO
 *
 * @param string $title  Panel title
 * @param array $options An array of optional attributes & values to apply to the title element markup.
 *
 * @since tribe-square-one 1.0
 */

function the_panel_title( $title = null, $options = [] ) {

	if ( empty( $title ) && ! is_panel_preview() ) {
		return;
	}

	$defaults = [
		'classes'       => '',    // String: Space-separated CSS classes to apply to the HTML element
		'data_name'     => null,  // String: data-name attribute value used in live preview mode to identify the title that should be updated.
		'data_livetext' => false, // Bool: Should this panel title be live-update enabled for live previews?
		'depth'         => 0,     // Int: The depth value for this panel when live-updating in preview mode.
		'index'         => 0,     // Int: The index value for this panel when live-updating in preview mode.
	];

	$options = wp_parse_args( $options, $defaults );

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

	$class      = ' class="site-panel__title ' . esc_attr( $options[ 'classes' ] ) . '"';
	$data_attrs = ( ! empty( $options[ 'data_name' ] ) ) ? ' data-name="' . esc_attr( $options[ 'data_name' ] ) . '"' : '';
	$data_attrs .= ( true === $options[ 'data_livetext' ] ) ? ' data-livetext' : '';
	$data_attrs .= ( true === $options[ 'data_livetext' ] ) ? sprintf( ' data-depth="%d"', $options[ 'depth' ] ) : '';
	$data_attrs .= ( true === $options[ 'data_livetext' ] ) ? sprintf( ' data-index="%d"', $options[ 'index' ] ) : '';

	$panel_title = sprintf(
		'<%1$s%2$s%3$s>%4$s</%1$s>',
		esc_attr( $h_level ),
		$class,
		$data_attrs,
		$title
	);

	echo $panel_title;
}

/**
 * Output panel description
 *
 * @param string $content  Panel content
 * @param array $options An array of optional attributes & values to apply to the description element markup.
 *
 * @since tribe-square-one 1.0
 */

function the_panel_description( $content = null, $options = [] ) {

	if ( empty( $content ) && ! is_panel_preview() ) {
		return;
	}

	$defaults = [
		'classes'       => '',    // String: Space-separated CSS classes to apply to the HTML element
		'data_name'     => null,  // String: data-name attribute value used in live preview mode to identify the field that should be updated.
		'data_livetext' => false, // Bool: Should this panel description be live-update enabled for live previews?
		'data_autop'    => false, // Bool: Is this panel description powered by a WYSIWYG field?
		'depth'         => 0,     // Int: The depth value for this panel when live-updating in preview mode.
		'index'         => 0,     // Int: The index value for this panel when live-updating in preview mode.
	];

	$options = wp_parse_args( $options, $defaults );

	$class      = ' class="site-panel__description ' . esc_attr( $options[ 'classes' ] ) . '"';
	$data_attrs = ( ! empty( $options[ 'data_name' ] ) ) ? ' data-name="' . esc_attr( $options[ 'data_name' ] ) . '"' : '';
	$data_attrs .= ( true === $options[ 'data_livetext' ] ) ? ' data-livetext' : '';
	$data_attrs .= ( true === $options[ 'data_livetext' ] ) ? sprintf( ' data-depth="%d"', $options[ 'depth' ] ) : '';
	$data_attrs .= ( true === $options[ 'data_livetext' ] ) ? sprintf( ' data-index="%d"', $options[ 'index' ] ) : '';
	$data_attrs .= ( true === $options[ 'data_livetext' ] ) ? sprintf( ' data-autop="%d"', $options[ 'data_autop' ] ) : '';

	$panel_description = sprintf(
		'<div%1$s%2$s>%3$s</div>',
		$class,
		$data_attrs,
		$content
	);

	echo $panel_description;
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
