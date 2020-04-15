<?php

namespace Tribe\Project\Assets\Admin;


class Styles {
	/**
	 * @var Admin_Build_Parser
	 */
	private $build_parser;

	public function __construct( Admin_Build_Parser $build_parser ) {
		$this->build_parser = $build_parser;
	}

	/**
	 * Register all styles for later enqueue
	 *
	 * @return void
	 * @action current_screen
	 */
	public function register_styles(): void {
		foreach ( $this->build_parser->get_styles() as $handle => $asset ) {
			wp_register_style( $handle, $asset['uri'], $asset['dependencies'], $asset['version'], $asset['media'] );
		}
	}

	/**
	 * Enqueue the styles we need on all admin screens
	 *
	 * @action admin_enqueue_scripts
	 */
	public function enqueue_styles(): void {
		wp_enqueue_style( 'tribe-styles-master' );
	}

	/**
	 * Add a stylesheet to the login page
	 *
	 * @action login_enqueue_scripts
	 */
	public function enqueue_login_styles() {
		wp_enqueue_style( 'tribe-styles-login' );

	}
}
