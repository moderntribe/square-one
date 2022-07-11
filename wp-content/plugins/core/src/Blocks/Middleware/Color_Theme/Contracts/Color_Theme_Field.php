<?php declare(strict_types=1);

namespace Tribe\Project\Blocks\Middleware\Color_Theme\Contracts;

use Tribe\Libs\ACF\Field;

interface Color_Theme_Field {

	/**
	 * Retrieve the color theme field configuration.
	 *
	 * @param string $key_prefix The field's key prefix, often the Block::NAME constant.
	 * @param string $label The already translated label for the field.
	 */
	public function get_field( string $key_prefix, string $label = '' ): Field;

}
