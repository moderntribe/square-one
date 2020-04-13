<?php


namespace Tribe\Project\Theme\Editor;


class Editor_Styles {
	/**
	 * Register styles with the block editor.
	 *
	 * Uses the relative path, because the block editor will actually grab
	 * the file from the theme directory and apply some changes to it.
	 *
	 * @see https://developer.wordpress.org/block-editor/developers/themes/theme-support/#editor-styles
	 *
	 * @action admin_init
	 */
	public function block_editor_styles() {
		add_editor_style( $this->theme_stylesheet_path() );
		add_theme_support( 'editor-styles' );
	}

	/**
	 * Add a body class to the visual editor
	 *
	 * @filter tiny_mce_before_init
	 */
	public function mce_editor_body_class( $settings ) {

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
	public function mce_editor_styles( $styles ) {
		$theme_uri = get_theme_file_uri( $this->theme_stylesheet_path() );
		$styles    = array_diff( $styles, [ $theme_uri ] );
		$styles[]  = get_theme_file_uri( $this->mce_stylesheet_path() );

		return $styles;
	}

	/**
	 * @return string The relative path to the theme stylesheet
	 */
	private function theme_stylesheet_path(): string {
		return 'assets/css/dist/theme/master' . $this->file_suffix();
	}

	/**
	 * @return string The relative path to the MCE stylesheet
	 */
	private function mce_stylesheet_path(): string {
		return 'assets/css/dist/admin/editor-style' . $this->file_suffix();
	}

	/**
	 * @return string The file suffix to use for the stylesheet, based on the SCRIPT_DEBUG configuration
	 */
	private function file_suffix(): string {
		return ! defined( 'SCRIPT_DEBUG' ) || SCRIPT_DEBUG === false ? '.min.css' : '.css';
	}
}
