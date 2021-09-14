<?php declare(strict_types=1);

namespace Tribe\Project\Assets\Admin;

class Styles {

	private Admin_Build_Parser $build_parser;

	public function __construct( Admin_Build_Parser $build_parser ) {
		$this->build_parser = $build_parser;
	}

	/**
	 * Register all styles for later enqueue
	 *
	 * @return void
	 *
	 * @action admin_init
	 */
	public function register_styles(): void {
		// If constant is true, set version to current timestamp, forcing cache invalidation on every page load
		$timestamp = defined( 'ASSET_VERSION_TIMESTAMP' ) && ASSET_VERSION_TIMESTAMP === true ? time() : null;
		foreach ( $this->build_parser->get_styles() as $handle => $asset ) {
			wp_register_style( $handle, $asset['uri'], $asset['dependencies'], $timestamp ?? $asset['version'], $asset['media'] );
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

	/**
	 * This function removes the `wp-reset-editor-styles` style dependency from the
	 * block editor styles enqueue.
	 *
	 * The Block Editor in WP Core v5.8.0 is enqueuing a reset.css file which overrides
	 * many of our baseline typographic styles for paragraphs, headings, etc. due to the
	 * specificity applied inside the `.editor-styles-wrapper` class.
	 *
	 * This function may well be able to removed when the block editor is fully iframed
	 * per the Gutenberg issue discussion linked below.
	 *
	 * @link https://github.com/WordPress/gutenberg/pull/33522 for context.
	 *
	 * @action admin_enqueue_scripts
	 */
	public function remove_editor_style_reset(): void {
		if ( ! isset( wp_styles()->registered['wp-edit-blocks']->deps ) ) {
			return;
		}

		$wp_edit_blocks_dependencies                    = array_diff( wp_styles()->registered['wp-edit-blocks']->deps, [ 'wp-reset-editor-styles' ] );
		wp_styles()->registered['wp-edit-blocks']->deps = $wp_edit_blocks_dependencies;
	}

}
