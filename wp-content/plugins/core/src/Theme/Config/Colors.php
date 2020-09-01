<?php

namespace Tribe\Project\Theme\Config;

class Colors {

	/**
	 * @var array
	 */
	private $colors;

	/**
	 * Colors constructor.
	 *
	 * @param array $colors An array of color definitions.
	 *                      Keys should be unique identifiers for the colors.
	 *                      Values should contain an array of:
	 *                      - color - string - a CSS hex value (including the hash)
	 *                      - label - string - the translated label for the color
	 */
	public function __construct( array $colors ) {
		$this->colors = $colors;
	}

	/**
	 * Get a list of color options. These should mirror the colors
	 * defined in utilities/helper-classes/_colors.pcss in the theme.
	 *
	 * @return array
	 */
	public function get_colors(): array {
		return $this->colors;
	}

	/**
	 * Return a subset of the theme color options list.
	 *
	 * @param string[] $color_keys
	 *
	 * @return self
	 */
	public function get_subset( array $color_keys = [] ): self {
		return new self( array_intersect_key( $this->colors, array_flip( $color_keys ) ) );
	}

	/**
	 * Formats the colors as `[ 'name' => <label>, 'slug' => <key>, 'color' => <color> ]`
	 *
	 * This is the format expected by the block editor.
	 *
	 * @return array
	 * @see https://developer.wordpress.org/block-editor/developers/themes/theme-support/#block-color-palettes
	 */
	public function format_for_blocks(): array {
		$colors = [];
		foreach ( $this->colors as $key => $value ) {
			$colors[] = [
				'name'  => $value['label'],
				'slug'  => $key,
				'color' => $value['color'],
			];
		}

		return $colors;
	}

	/**
	 * Formats the colors as `[ <color> => <label> ]`
	 *
	 * This is the format expected by an ACF Swatch field.
	 *
	 * @return array
	 */
	public function format_for_acf(): array {
		return wp_list_pluck( $this->colors, 'label', 'color' );
	}

	/**
	 * Return a color's key (I.e. - `black`) from the color's value (I.e. - `#000000`).
	 *
	 * Useful for getting a color's key name for use with CSS classes from the color's value
	 * such as when grabbing the value from an ACF swatch field.
	 *
	 * @param string $hex
	 *
	 * @return string
	 */
	public function get_name_by_value( $hex = '' ): string {
		foreach ( $this->colors as $key => $value ) {
			if ( $value['color'] === $hex ) {
				return $key;
			}
		}

		return '';
	}


}
