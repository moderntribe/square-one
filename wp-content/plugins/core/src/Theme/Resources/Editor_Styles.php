<?php


namespace Tribe\Project\Theme\Resources;

class Editor_Styles {
	/**
	 * Visual Editor Styles
	 * @action after_setup_theme
	 */
	public function visual_editor_styles() {

		$css_dir    = trailingslashit( get_stylesheet_directory_uri() ) . 'css/admin/';
		$editor_css = 'editor-style.css';

		// Production
		if ( ! defined( 'SCRIPT_DEBUG' ) || SCRIPT_DEBUG === false ) {
			$editor_css = 'dist/editor-style.min.css';
		}

		add_editor_style( $css_dir . $editor_css );
	}
	/**
	 * Visual Editor Body Class
	 * @filter tiny_mce_before_init
	 */
	public function visual_editor_body_class( $settings ) {

		$settings['body_class'] = ( $settings['body_class'] ?? '' ) . ' t-content';

		return $settings;
	}
}
