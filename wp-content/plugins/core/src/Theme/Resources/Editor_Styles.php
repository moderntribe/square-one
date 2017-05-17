<?php


namespace Tribe\Project\Theme\Resources;


class Editor_Styles {

	/** @var string Path to the root file of the plugin */
	private $plugin_file = '';

	public function __construct( $plugin_file = '' ) {
		$this->plugin_file = $plugin_file;
	}

	/**
	 * Visual Editor Styles
	 * @action after_setup_theme
	 */
	public function visual_editor_styles() {

		$css_dir    = $this->get_css_url();
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
	 * @filter tiny_mce_before_init
	 */
	public function visual_editor_body_class( $settings ) {

		$settings['body_class'] .= ' t-content';

		return $settings;

	}

	private function get_css_url() {
		return plugins_url( 'assets/theme/css/admin/', $this->plugin_file );
	}

	/**
	 * Filter TinyMCE Buttons
	 *
	 * @param $buttons array
	 * @return $buttons array
	 * @filter mce_buttons
	 */
	public function mce_buttons( $buttons ) {
		$tag_select = array_shift( $buttons );
		array_unshift( $buttons, $tag_select, 'styleselect' );
		return $buttons;
	}

	/**
	 * Visual Editor Style Tags Drop-Down
	 *
	 * Adds a Formats dropdown to the right of the element selector for TinyMCE instances.
	 *
	 * @link Screenshot: http://p.tri.be/l2nG2
	 * @link TinyMCE Documentation: http://archive.tinymce.com/wiki.php/Configuration3x:style_formats
	 *
	 * @param $settings
	 * @return $ettings string
	 * @filter tiny_mce_before_init
	 */
	public function visual_editor_styles_dropdown( $settings ) {
		$style_formats = [
			/* Example single-level format */
			[
				'title'    => __( 'Button', 'tribe' ),
				'selector' => 'a',
				'classes'  => 'btn',
				'wrapper'  => false,
			],
			/* Example multi-level format */
			[
				'title' => __( 'Link Styles', 'tribe' ),
				'items' => [
					[
						'title'    => __( 'CTA Link', 'tribe' ),
						'selector' => 'a',
						'classes'  => 'cta',
						'wrapper'  => false,
					],
					[
						'title'    => __( 'CTA Arrow Link', 'tribe' ),
						'selector' => 'a',
						'classes'  => 'cta--arrow-right',
						'wrapper'  => false,
					],
				],
			],
		];
		$settings[ 'style_formats' ] = json_encode( $style_formats );
		return $settings;
	}
}
