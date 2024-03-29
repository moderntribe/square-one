<?php declare(strict_types=1);

namespace Tribe\Project\Admin\Editor;

class Classic_Editor_Formats {

	public const MINIMAL = 'minimal';

	/**
	 * Filter "Basic" or "Teeny" TinyMCE Buttons
	 *
	 * Adjusts the buttons added to the "teeny" or "basic" TinyMCE editor.
	 *
	 * @param array  $buttons
	 * @param string $editor_id
	 *
	 * @return string[]
	 */
	public function teeny_mce_buttons( array $buttons, string $editor_id ): array {
		// Remove lists, underline, undo, redo, blockquote, alignments, fullscreen
		// Add Format Select & Style Select
		return [
			'formatselect',
			'styleselect',
			'bold',
			'italic',
			'link',
		];
	}

	/**
	 * Filter TinyMCE Buttons
	 *
	 * @filter mce_buttons
	 *
	 * @param array $buttons
	 *
	 * @return array
	 */
	public function mce_buttons( array $buttons ): array {
		// Remove formatselect
		$tag_select = array_shift( $buttons );

		// Prepend styleselect & Re-Prepend formatselect
		array_unshift( $buttons, $tag_select, 'styleselect' );

		// Remove WP More
		$key = array_search( 'wp_more', $buttons );
		if ( false !== $key ) {
			unset( $buttons[ $key ] );
		}

		return $buttons;
	}

	/**
	 * Visual Editor Style Tags Drop-Down
	 *
	 * Adds a "Formats" dropdown to the right of the element selector for TinyMCE instances.
	 *
	 * @see    http://p.tri.be/l2nG2 Screenshot
	 * @see    http://archive.tinymce.com/wiki.php/Configuration3x:style_formats TinyMCE Documentation
	 *
	 * @filter tiny_mce_before_init
	 *
	 * @param array $settings
	 *
	 * @return array $settings
	 */
	public function visual_editor_styles_dropdown( array $settings ): array {
		$style_formats = [
			/* Example single-level format
			[
				'title'    => __( 'Button', 'tribe' ),
				'selector' => 'a',
				'classes'  => 'a-btn',
				'wrapper'  => false,
			],
			 */
			[
				'title' => __( 'Button & Link Styles', 'tribe' ),
				'items' => [
					[
						'title'    => __( 'Button', 'tribe' ),
						'selector' => 'a',
						'classes'  => 'a-btn',
						'wrapper'  => false,
					],
					[
						'title'    => __( 'Button Secondary', 'tribe' ),
						'selector' => 'a',
						'classes'  => 'a-btn-secondary',
						'wrapper'  => false,
					],
					[
						'title'    => __( 'Button Tertiary', 'tribe' ),
						'selector' => 'a',
						'classes'  => 'a-btn-tertiary',
						'wrapper'  => false,
					],
					[
						'title'    => __( 'CTA Link', 'tribe' ),
						'selector' => 'a',
						'classes'  => 'a-cta',
						'wrapper'  => false,
					],
				],
			],
		];

		$settings['style_formats'] = json_encode( $style_formats );

		return $settings;
	}

	/**
	 * Add minimal toolbar to use as default editor with custom blocks
	 *
	 * @filter acf/fields/wysiwyg/toolbars
	 *
	 * @param array $toolbars
	 *
	 * @return array
	 */
	public function add_minimal_toolbar( array $toolbars ): array {
		$toolbars[ self::MINIMAL ][1] = [
			'bold',
			'italic',
			'bullist',
			'numlist',
			'link',
		];

		return $toolbars;
	}

}
