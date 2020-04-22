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
		foreach ( $this->build_parser->get_styles() as $handle => $asset ) {
			wp_register_style( $handle, $asset['uri'], $asset['dependencies'], $asset['version'], $asset['media'] );
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

	/**
	 * Dequeue WP Core's default block styles from the public FE.
	 *
	 * @action wp_enqueue_scripts
	 */
	public function dequeue_block_styles(): void {
		wp_dequeue_style( 'wp-block-library' );
	}
}
