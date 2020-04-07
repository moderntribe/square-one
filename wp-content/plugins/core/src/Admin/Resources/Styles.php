<?php

namespace Tribe\Project\Admin\Resources;


class Styles {
	/**
	 * Enqueue admin styles related to the react app
	 */
	public function enqueue_styles() {
		$debug = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;

		$css_file = $debug ? 'master.css' : 'master.min.css';
		$css_src = trailingslashit( get_stylesheet_directory_uri() ) . 'assets/css/dist/admin/' . $css_file;

		wp_enqueue_style( 'tribe-admin-styles', $css_src, [] );
	}
}
