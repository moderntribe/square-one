<?php


namespace Tribe\Project\Assets\Theme;


class Styles {
	/**
	 * @var Theme_Build_Parser
	 */
	private $build_parser;

	public function __construct( Theme_Build_Parser $build_parser ) {
		$this->build_parser = $build_parser;
	}

	/**
	 * Register all styles for later enqueue
	 *
	 * @return void
	 * @action template_redirect
	 */
	public function register_styles(): void {
		// todo: @jbrinley please abstract as you see fit
		$version = defined( 'CSS_VERSION_TIMESTAMP' ) && CSS_VERSION_TIMESTAMP === true ? time() : null;
		foreach ( $this->build_parser->get_styles() as $handle => $asset ) {
			wp_register_style( $handle, $asset['uri'], $asset['dependencies'], $version ?? $asset['version'], $asset['media'] );
		}
	}

	/**
	 * Enqueue the styles we need for the current page
	 *
	 * @action wp_enqueue_scripts
	 */
	public function enqueue_styles(): void {
		// enqueue all non-legacy handles
		foreach ( $this->build_parser->get_non_legacy_style_handles() as $handle ) {
			wp_enqueue_style( $handle );
		}
	}
}
