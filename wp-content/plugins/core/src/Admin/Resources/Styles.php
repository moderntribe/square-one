<?php

namespace Tribe\Project\Admin\Resources;


class Styles {
	/**
	 * Enqueue admin styles related to the react app
	 */
	public function enqueue_styles() {
		$debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;

		$css_file = $debug ? 'master.css' : 'dist/master.min.css';
		$css_src = trailingslashit( get_stylesheet_directory_uri() ) . 'css/admin/' . $css_file;

		wp_enqueue_style( 'tribe-admin-styles', $css_src, [] );
	}
}