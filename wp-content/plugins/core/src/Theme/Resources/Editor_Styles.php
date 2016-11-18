<?php


namespace Tribe\Project\Theme\Resources;


class Editor_Styles {
	public function hook() {
		add_action( 'after_setup_theme', [ $this, 'visual_editor_styles' ], 10, 0 );
		add_filter( 'tiny_mce_before_init', [ $this, 'visual_editor_body_class' ], 10, 1 );
	}

	/**
	 * Visual Editor Styles
	 */
	public function visual_editor_styles() {

		$css_dir    = trailingslashit( get_template_directory_uri() ) . 'css/admin/';
		$editor_css = 'editor-style.css';

		// Production
		if ( ! defined( 'SCRIPT_DEBUG' ) || SCRIPT_DEBUG === false ) {
			$css_dir    = trailingslashit( get_template_directory_uri() ) . 'css/admin/dist/';
			$editor_css = 'editor-style.min.css';
		}

		add_editor_style( $css_dir . $editor_css );

	}
	/**
	 * Visual Editor Body Class
	 */
	public function visual_editor_body_class( $settings ) {

		$settings['body_class'] .= ' t-content';

		return $settings;

	}
}
