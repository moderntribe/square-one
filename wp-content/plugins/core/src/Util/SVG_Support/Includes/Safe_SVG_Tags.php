<?php

namespace Tribe\Project\Util\SVG_Support\Includes;

class Safe_SVG_Tags extends \enshrined\svgSanitize\data\AllowedTags {

	/**
	 * Returns an array of tags
	 *
	 * @return array
	 */
	public static function getTags() {

		/**
		 * var  array Tags that are allowed.
		 */
		return apply_filters( 'tribe_svg_allowed_tags', parent::getTags() );
	}
}