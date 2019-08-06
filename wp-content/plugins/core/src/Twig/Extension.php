<?php

namespace Tribe\Project\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class Extension extends AbstractExtension {
	public function getFilters() {
		return [
			new TwigFilter( 'strip_shortcodes', 'strip_shortcodes' ),
			new TwigFilter( 'wp_trim_words', 'wp_trim_words' ),
			new TwigFilter( 'sanitize_title', 'sanitize_title' ),
			new TwigFilter( 'wpautop', 'wpautop' ),
			new TwigFilter( 'apply_filters', function () {
				$args = func_get_args();
				$tag  = current( array_splice( $args, 1, 1 ) );

				return apply_filters_ref_array( $tag, $args );
			} ),
			new TwigFilter( 'esc_url', 'esc_url' ),
			new TwigFilter( 'esc_attr', 'esc_attr' ),
			new TwigFilter( 'esc_html', 'esc_html' ),
			new TwigFilter( 'esc_js', 'esc_js' ),
			new TwigFilter( 'print_r', function ( $arg ) {
				return print_r( $arg, true );
			} ),
		];
	}

	public function getFunctions() {
		return [
			new TwigFunction( 'do_action', 'do_action' ),
			new TwigFunction( 'do_shortcode', 'do_shortcode' ),
			new TwigFunction( 'bloginfo', function ( $show = '', $filter = 'raw' ) {
				return get_bloginfo( $show, $filter );
			} ),
			new TwigFunction( '__', function ( $string ) {
				return $string; // for multilingual projects, use: return __( $string, 'tribe' );
			} ),
		];
	}


}