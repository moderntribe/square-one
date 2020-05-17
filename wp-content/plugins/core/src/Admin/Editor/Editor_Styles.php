<?php


namespace Tribe\Project\Admin\Editor;


use Tribe\Project\Assets\Admin\Admin_Build_Parser;
use Tribe\Project\Assets\Build_Parser;

class Editor_Styles {

	/**
	 * @var Admin_Build_Parser
	 */
	private $admin_build_parser;

	public function __construct( Admin_Build_Parser $admin_build_parser ) {
		$this->admin_build_parser = $admin_build_parser;
	}

	/**
	 * Remove the block editor styles from editor-styles.css injected by WP core.
	 *
	 * @param array $editor_settings
	 *
	 * @return array
	 * @filter block_editor_settings
	 * @see    edit-form-blocks.php
	 *
	 */
	public function remove_core_block_editor_styles( array $editor_settings ): array {
		$editor_settings['styles'] = array_slice( $editor_settings['styles'], 1 );

		return $editor_settings;
	}

	/**
	 * Enqueue our block editor styles.
	 *
	 * Using wp_enqueue_style instead of add_editor_style, because
	 * we don't trust WordPress to effectively pre-process the styles
	 * to apply to the block editor. We handle that with postcss.
	 *
	 * @see    https://developer.wordpress.org/block-editor/developers/themes/theme-support/#editor-styles
	 *
	 * @action enqueue_block_editor_assets
	 */
	public function enqueue_block_editor_styles(): void {
		wp_enqueue_style( 'tribe-styles-block-editor' );
	}

	/**
	 * Add a body class to the MCE visual editor
	 *
	 * @param array $settings
	 *
	 * @return array
	 * @filter tiny_mce_before_init
	 */
	public function mce_editor_body_class( $settings ): array {

		$settings['body_class'] = ( $settings['body_class'] ?? '' ) . ' s-sink t-sink';

		return $settings;

	}

	/**
	 * Add our editor styles to the MCE editor.
	 *
	 * Since we are not declaring theme support for editor-styles,
	 * this will only be enqueued in the MCE editor iframe.
	 *
	 * @return void
	 * @filter admin_init
	 */
	public function enqueue_mce_editor_styles(): void {
		add_editor_style( $this->mce_stylesheet_path() );
	}

	/**
	 * @return string The relative path to the MCE stylesheet
	 */
	private function mce_stylesheet_path(): string {
		return $this->get_asset_path( 'tribe-styles-mce-editor', $this->admin_build_parser );
	}

	/**
	 * Get the path to the asset file, relative to the theme directory, from the given build parser
	 *
	 * @param string       $handle
	 *
	 * @param Build_Parser $build_parser
	 *
	 * @return string
	 */
	private function get_asset_path( $handle, Build_Parser $build_parser ): string {
		$styles = $build_parser->get_styles();
		if ( ! array_key_exists( $handle, $styles ) ) {
			return '';
		}

		return $styles[ $handle ]['file'];
	}
}
