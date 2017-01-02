<?php

namespace Tribe\Project\Theme;

abstract class Util {

	/**
	 * Convert an array into an HTML class attribute string
	 *
	 * @param array $classes
	 * @param bool  $attribute
	 *
	 * @return string
	 */
	public static function class_attribute( $classes, $attribute = true ) {
		if ( empty( $classes ) ) {
			return '';
		}

		return sprintf(
			'%s%s%s',
			$attribute ? ' class="' : '',
			implode( ' ', array_unique( $classes ) ),
			$attribute ? '"' : ''
		);
	}

}
