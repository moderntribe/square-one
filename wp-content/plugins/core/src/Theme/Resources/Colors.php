<?php

namespace Tribe\Project\Theme\Resources;

class Colors {

	CONST BLACK = 'black';
	CONST WHITE = 'white';

	/**
	 * Get a list of color options. These should mirror the colors
	 * defined in utilities/helper-classes/_colors.pcss in the theme.
	 *
	 * @return array
	 */

	public static function get_color_options() {
		return [
			static::WHITE => [ 'color' => '#ffffff', 'label' => __( 'White', 'tribe' ) ],
			static::BLACK => [ 'color' => '#000000', 'label' => __( 'Black', 'tribe' ) ],
		];
	}

	/**
	 * Return a subset of the theme color options list.
	 *
	 * @param array $colors
	 *
	 * @return array
	 */

	public static function get_color_options_subset( array $colors = [] ) {
		return array_intersect_key( self::get_color_options(), array_flip( $colors ) );
	}

	/**
	 * Create an array of color options for use with the ACF Swatch field.
	 *
	 * @param array $colors
	 *
	 * @return array
	 */
	public static function get_color_options_for_acf( array $colors = [] ) {
		$colors     = array_intersect_key( self::get_color_options(), array_flip( $colors ) );
		$acf_colors = [];
		foreach ( $colors as $color ) {
			$acf_colors[ $color[ 'color' ] ] = $color[ 'label' ];
		}

		return $acf_colors;
	}

	/**
	 * Return a color's key (I.e. - `black`) from the color's hex value (I.e. - `#000000`).
	 *
	 * Useful for getting a color's key name for use with CSS classes from the color's value
	 * like when grabbing the value from an ACF swatch field.
	 *
	 * @param string $color
	 *
	 * @return string
	 */
	public static function get_color_key_from_acf_value( $color = '' ) {
		$name = '';
		foreach ( self::get_color_options() as $key => $value ) {
			if ( in_array( $color, $value ) ) {
				$name = $key;
				break;
			}
		}

		return $name;
	}


}
