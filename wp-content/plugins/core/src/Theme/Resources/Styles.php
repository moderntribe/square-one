<?php


namespace Tribe\Project\Theme\Resources;

class Styles {
	/**
	 * Enqueue styles
	 *
	 * @action wp_enqueue_scripts
	 */
	public function enqueue_styles() {

		$css_dir = trailingslashit( get_stylesheet_directory_uri() ) . 'css/';
		$version = tribe_get_version();

		if ( ! defined( 'SCRIPT_DEBUG' ) || SCRIPT_DEBUG === false ) { // Production
			$css_global = 'dist/master.min.css';
			$css_print  = 'dist/print.min.css';
		} else { // Dev
			$css_global = 'master.css';
			$css_print  = 'print.css';
		}

		// CSS: base
		wp_enqueue_style( 'core-theme-base', $css_dir . $css_global, [], $version, 'all' );

		// CSS: print
		wp_enqueue_style( 'core-theme-print', $css_dir . $css_print, [], $version, 'print' );

	}
}
