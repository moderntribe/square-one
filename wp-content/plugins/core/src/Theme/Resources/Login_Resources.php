<?php

namespace Tribe\Project\Theme\Resources;

class Login_Resources {

	/** @var string Path to the root file of the plugin */
	private $plugin_file = '';

	public function __construct( $plugin_file = '' ) {
		$this->plugin_file = $plugin_file;
	}

	/**
	 * Add a stylesheet to the login page
	 * @action login_enqueue_scripts
	 */
	public function login_styles() {

		$css_dir = $this->get_css_url();
		$version = tribe_get_version();

		// CSS
		$css_login = 'login.css';

		// Production
		if ( !defined( 'SCRIPT_DEBUG' ) || SCRIPT_DEBUG === false ) {
			$css_login = 'dist/login.min.css';
		}

		wp_enqueue_style( 'core-theme-login', $css_dir . $css_login, $version );

	}

	private function get_css_url() {
		return plugins_url( 'assets/theme/css/admin/', $this->plugin_file );
	}
}
