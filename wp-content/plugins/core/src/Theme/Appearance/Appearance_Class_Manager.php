<?php declare(strict_types=1);

namespace Tribe\Project\Theme\Appearance;

use OzdemirBurak\Iris\BaseColor;
use OzdemirBurak\Iris\Color\Hex;
use OzdemirBurak\Iris\Exceptions\InvalidColorException;
use Tribe\Project\Object_Meta\Appearance\Appearance;

/**
 * Manage Appearance CSS class functionality.
 *
 * @package Tribe\Project\Theme\Appearance
 */
class Appearance_Class_Manager {

	/**
	 * Return a class based on a hex code.
	 *
	 * @param string $hex The #hex color code.
	 *
	 * @return string The Light or Dark Class.
	 */
	public function get_class_from_hex( string $hex ): string {
		try {
			$color = $this->get_color( $hex );
		} catch ( InvalidColorException $e ) {
			return Appearance::DEFAULT_CSS_CLASS;
		}

		return $color->isDark() ? Appearance::CSS_DARK_CLASS : Appearance::CSS_LIGHT_CLASS;
	}

	/**
	 * Get a hex color instance.
	 *
	 * @param string $hex The #hex color code.
	 *
	 * @return BaseColor
	 *
	 * @throws InvalidColorException
	 */
	protected function get_color( string $hex ): BaseColor {
		return new Hex( $hex );
	}

}
