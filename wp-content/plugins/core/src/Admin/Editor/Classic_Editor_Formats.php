<?php


namespace Tribe\Project\Admin\Editor;


class Classic_Editor_Formats {

	/**
	 * Filter TinyMCE Buttons
	 *
	 * @param array $buttons
	 *
	 * @return array $buttons
	 * @filter mce_buttons
	 */
	public function mce_buttons( $buttons ): array {
		$tag_select = array_shift( $buttons );
		array_unshift( $buttons, $tag_select, 'styleselect' );

		return $buttons;
	}

	/**
	 * Visual Editor Style Tags Drop-Down
	 *
	 * Adds a Formats dropdown to the right of the element selector for TinyMCE instances.
	 *
	 * @see    http://p.tri.be/l2nG2 Screenshot
	 * @see    http://archive.tinymce.com/wiki.php/Configuration3x:style_formats TinyMCE Documentation
	 *
	 * @param array $settings
	 *
	 * @return array $settings
	 * @filter tiny_mce_before_init
	 */
	public function visual_editor_styles_dropdown( $settings ): array {
		$style_formats             = [
			/* Example single-level format */
			[
				'title'    => __( 'Button', 'tribe' ),
				'selector' => 'a',
				'classes'  => 'c-btn',
				'wrapper'  => false,
			],
			/* Example multi-level format */
			[
				'title' => __( 'Link Styles', 'tribe' ),
				'items' => [
					[
						'title'    => __( 'CTA Link', 'tribe' ),
						'selector' => 'a',
						'classes'  => 'c-btn-text',
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
		$settings['style_formats'] = json_encode( $style_formats );

		return $settings;
	}
}
