<?php

namespace Tribe\Project\Util\SVG_Support\Includes;

class Safe_SVG_Attributes extends \enshrined\svgSanitize\data\AllowedAttributes {

	/**
	 * Returns an array of attributes
	 *
	 * @return array
	 */
	public static function getAttributes() {

		/**
		 * var  array Attributes that are allowed.
		 */
		return apply_filters( 'tribe_svg_allowed_attributes', parent::getAttributes() );
	}
}