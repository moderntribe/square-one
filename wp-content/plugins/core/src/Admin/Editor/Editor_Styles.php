<?php


namespace Tribe\Project\Admin\Editor;


use Tribe\Project\Assets\Admin\Admin_Build_Parser;
use Tribe\Project\Assets\Build_Parser;
use Tribe\Project\Assets\Theme\Theme_Build_Parser;

class Editor_Styles {

	/**
	 * @var Admin_Build_Parser
	 */
	private $admin_build_parser;
	/**
	 * @var Theme_Build_Parser
	 */
	private $theme_build_parser;

	public function __construct( Admin_Build_Parser $admin_build_parser, Theme_Build_Parser $theme_build_parser ) {
		$this->admin_build_parser = $admin_build_parser;
		$this->theme_build_parser = $theme_build_parser;
	}

	/**
	 * Register styles with the block editor.
	 *
	 * Uses the relative path, because the block editor will actually grab
	 * the file from the theme directory and apply some changes to it.
	 *
	 * @see    https://developer.wordpress.org/block-editor/developers/themes/theme-support/#editor-styles
	 *
	 * @action admin_init
	 */
	public function block_editor_styles(): void {
		add_editor_style( $this->theme_stylesheet_path() );
		add_theme_support( 'editor-styles' );
	}

	/**
	 * Add a body class to the visual editor
	 *
	 * @param array $settings
	 *
	 * @return array
	 * @filter tiny_mce_before_init
	 */
	public function mce_editor_body_class( $settings ): array {

		$settings['body_class'] = ( $settings['body_class'] ?? '' ) . ' t-content';

		return $settings;

	}

	/**
	 * Filter the styles sent to the MCE editor.
	 *
	 * We don't want to apply the full block editor styles, so
	 * this will remove those and replace them with the CSS
	 * specific to the MCE editor
	 *
	 * @param array $styles
	 *
	 * @return array
	 * @filter editor_stylesheets
	 */
	public function mce_editor_styles( $styles ): array {
		$theme_uri = get_theme_file_uri( $this->theme_stylesheet_path() );
		$styles    = array_diff( $styles, [ $theme_uri ] );
		$styles[]  = get_theme_file_uri( $this->mce_stylesheet_path() );

		return $styles;
	}

	/**
	 * @return string The relative path to the theme stylesheet
	 */
	private function theme_stylesheet_path(): string {
		return $this->get_asset_path( 'tribe-styles-master', $this->theme_build_parser );
	}

	/**
	 * @return string The relative path to the MCE stylesheet
	 */
	private function mce_stylesheet_path(): string {
		return $this->get_asset_path( 'tribe-styles-editor-style', $this->admin_build_parser );
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
