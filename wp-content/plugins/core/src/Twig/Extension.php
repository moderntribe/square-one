<?php


namespace Tribe\Project\Twig;


class Extension extends \Twig_Extension {
	public function getFilters() {
		return [
			new \Twig_SimpleFilter( 'strip_shortcodes', 'strip_shortcodes' ),
			new \Twig_SimpleFilter( 'wp_trim_words', 'wp_trim_words' ),
			new \Twig_SimpleFilter( 'sanitize_title', 'sanitize_title' ),
			new \Twig_SimpleFilter( 'wpautop', 'wpautop' ),
			new \Twig_SimpleFilter( 'apply_filters', function () {
				$args = func_get_args();
				$tag  = current( array_splice( $args, 1, 1 ) );

				return apply_filters_ref_array( $tag, $args );
			} ),
			new \Twig_SimpleFilter( 'esc_url', 'esc_url' ),
			new \Twig_SimpleFilter( 'esc_attr', 'esc_attr' ),
			new \Twig_SimpleFilter( 'esc_html', 'esc_html' ),
			new \Twig_SimpleFilter( 'esc_js', 'esc_js' ),
			new \Twig_SimpleFilter( 'print_r', function ( $arg ) {
				return print_r( $arg, true );
			} ),
		];
	}

	public function getFunctions() {
		return [
			new \Twig_SimpleFunction( 'do_action', 'do_action' ),
			new \Twig_SimpleFunction( 'do_shortcode', 'do_shortcode' ),
			new \Twig_SimpleFunction( 'bloginfo', function ( $show = '', $filter = 'raw' ) {
				return get_bloginfo( $show, $filter );
			} ),
			new \Twig_SimpleFunction( '__', function ( $string ) {
				return $string; // for multilingual projects, use: return __( $string, 'tribe' );
			} ),
		];
	}


}