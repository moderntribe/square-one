<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Color_Theme\Contracts;

use Tribe\Libs\ACF\Field;

interface Color_Theme_Field {

	/**
	 * Retrieve the color theme field configuration.
	 *
	 * @param string $label The already translated label for the field.
	 *
	 * @return \Tribe\Libs\ACF\Field
	 */
	public function get_color_theme_field( string $label = '' ): Field;

}
