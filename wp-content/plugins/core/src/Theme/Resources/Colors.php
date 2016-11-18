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

}
