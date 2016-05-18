<?php

namespace Tribe\Project\Theme\Resources;

class Login_Resources {
	public function hook() {
		add_action( 'login_enqueue_scripts', [ $this, 'login_styles' ] );
	}

	/**
	 * Add a stylesheet to the login page
	 */
	public function login_styles() {

		$css_dir = trailingslashit( get_template_directory_uri() ) . 'css/admin/';
		$version = tribe_get_version();

		// CSS
		$css_login = 'login.css';

		// Production
		if ( !defined( 'SCRIPT_DEBUG' ) || SCRIPT_DEBUG === false ) {
			$css_login = 'dist/login.min.css';
		}

		wp_enqueue_style( 'core-theme-login', $css_dir . $css_login, $version );

	}
}