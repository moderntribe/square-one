<?php

namespace Tribe\Project\Theme;

abstract class Util {

	/**
	 * Convert an array into an HTML class attribute string
	 *
	 * @param array $classes
	 *
	 * @return string
	 */
	public static function class_attribute( $classes ) {

		if ( empty( $classes ) ) {
			return '';
		}

		return sprintf( ' class="%s"', implode( ' ', array_unique( $classes ) ) );
	}

}
