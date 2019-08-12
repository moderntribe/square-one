<?php


namespace Tribe\Project\Theme\Resources;


class Editor_Formats {

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
			/*
			[
				'title'    => __( 'Button', 'tribe' ),
				'selector' => 'a',
				'classes'  => 'c-btn',
				'wrapper'  => false,
			],
			*/
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
						'classes'  => 'c-btn-text--icon',
						'wrapper'  => false,
					],
				],
			],
			[
				'title' => __( 'Button Styles', 'tribe' ),
				'items' => [
					[
						'title'    => __( 'Button', 'tribe' ),
						'selector' => 'a',
						'classes'  => 'c-btn',
						'wrapper'  => false,
					],
					[
						'title'    => __( 'Button Outline', 'tribe' ),
						'selector' => 'a',
						'classes'  => 'c-btn-outline',
						'wrapper'  => false,
					],
				],
			],
		];
		$settings[ 'style_formats' ] = json_encode( $style_formats );
		return $settings;
	}
}
