<?php

namespace Tribe\Project\Theme\Resources;

class Login_Resources {
	/**
	 * Add a stylesheet to the login page
	 * @action login_enqueue_scripts
	 */
	public function login_styles() {

		$css_dir = trailingslashit( get_stylesheet_directory_uri() ) . 'css/admin/';
		$version = tribe_get_version();

		// CSS
		$css_login = 'login.css';

		// Production
		if ( ! defined( 'SCRIPT_DEBUG' ) || SCRIPT_DEBUG === false ) {
			$css_login = 'dist/login.min.css';
		}

		wp_enqueue_style( 'core-theme-login', $css_dir . $css_login, $version );

	}
}
